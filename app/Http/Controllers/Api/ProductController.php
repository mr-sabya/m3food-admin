<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category; // Needed for filtering
use App\Models\Brand;    // Needed for filtering
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // For potential advanced queries

class ProductController extends Controller
{
    /**
     * Display a listing of products for the shop page.
     * Includes filtering, sorting, and pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Product::active()
                        ->with('categories', 'brand'); // Eager load common relationships

        // --- Filtering ---
        if ($request->has('category') && $request->category !== '') {
            $slugs = explode(',', $request->category); // Allows multiple categories like ?category=slug1,slug2
            $query->whereHas('categories', function ($q) use ($slugs) {
                $q->whereIn('slug', $slugs);
            });
        }

        if ($request->has('brand') && $request->brand !== '') {
            $slugs = explode(',', $request->brand);
            $query->whereHas('brand', function ($q) use ($slugs) {
                $q->whereIn('slug', $slugs);
            });
        }

        if ($request->has('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('search') && $request->search !== '') {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('short_description', 'like', $searchTerm)
                  ->orWhere('long_description', 'like', $searchTerm);
            });
        }

        // Add more filters as needed (e.g., tags, attributes)

        // --- Sorting ---
        $sortBy = $request->input('sort_by', 'created_at'); // Default sort by creation date
        $sortOrder = $request->input('sort_order', 'desc'); // Default sort order descending

        // Validate sort_by to prevent SQL injection
        $allowedSorts = ['name', 'price', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        // --- Pagination ---
        $perPage = $request->input('per_page', 12); // Default 12 items per page
        $products = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'data' => $products,
        ], 200);
    }

    /**
     * Display the specified product for a single product page.
     * Includes all relevant relationships and details.
     *
     * @param  string  $slug The product slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($slug)
    {
        $product = Product::active()
                        ->where('slug', $slug)
                        ->with([
                            'vendor',
                            'categories:id,name,slug', // Select specific fields for categories
                            'brand:id,name,slug',
                            'images', // All product images
                            'variants.attributeValues.attribute:id,name,slug,display_type', // Variants with their attributes
                            'reviews.user:id,name', // Reviews with user info
                            'tags:id,name,slug',
                            'attributeValues.attribute:id,name,slug,display_type', // Specifications for normal products
                            'deals' => function($query) { // Active deals only
                                $query->active();
                            }
                        ])
                        ->first();

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found or is not active',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product details retrieved successfully',
            'data' => $product,
        ], 200);
    }

    /**
     * Get a limited number of top/featured products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function topProducts(Request $request)
    {
        $limit = $request->input('limit', 10); // Default to 10
        if (!is_numeric($limit) || $limit <= 0) {
            $limit = 10;
        }

        $products = Product::active()
                        ->featured() // Using your featured scope
                        ->with('categories:id,name,slug', 'brand:id,name,slug')
                        ->orderByDesc('created_at') // Or by a 'priority' field, sales count, etc.
                        ->limit($limit)
                        ->get();

        if ($products->isEmpty()) {
            // Fallback: if no featured products, get recent active ones
            $products = Product::active()
                                ->with('categories:id,name,slug', 'brand:id,name,slug')
                                ->orderByDesc('created_at')
                                ->limit($limit)
                                ->get();
        }

        return response()->json([
            'status' => 'success',
            'message' => "Top {$limit} products retrieved successfully",
            'data' => $products,
        ], 200);
    }

    /**
     * Get a list of recently viewed products based on provided slugs.
     * This relies on the frontend sending an array of product slugs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recentlyViewedProducts(Request $request)
    {
        $slugs = $request->input('slugs'); // Expects an array of slugs, e.g., ?slugs[]=product-a&slugs[]=product-b

        if (!is_array($slugs) || empty($slugs)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No product slugs provided for recently viewed products.',
                'data' => [],
            ], 400); // Bad request
        }

        $limit = $request->input('limit', 10); // Default to 10
        if (!is_numeric($limit) || $limit <= 0) {
            $limit = 10;
        }

        // Fetch products in the order of the provided slugs to maintain 'recently viewed' order
        $products = Product::active()
                        ->whereIn('slug', $slugs)
                        ->with('categories:id,name,slug', 'brand:id,name,slug')
                        ->limit($limit)
                        ->get()
                        ->sortBy(function ($product) use ($slugs) {
                            return array_search($product->slug, $slugs);
                        })->values(); // Re-index the collection

        return response()->json([
            'status' => 'success',
            'message' => "Recently viewed products retrieved successfully",
            'data' => $products,
        ], 200);
    }
}