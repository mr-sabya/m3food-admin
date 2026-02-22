<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of general categories (can be filtered).
     * This method is more flexible for various listing needs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Category::query()->active();

        // Filter for parent categories only
        if ($request->boolean('parent_only')) {
            $query->parentCategories();
        }

        // Filter for categories shown on homepage
        if ($request->boolean('homepage')) {
            $query->featuredOnHomepage();
        }

        // Eager load relationships if requested
        if ($request->boolean('with_children')) {
            $query->with('children');
        }
        if ($request->boolean('with_parent')) {
            $query->with('parent');
        }

        // Apply limit if specified
        if ($request->has('limit') && is_numeric($request->limit)) {
            $query->limit((int)$request->limit);
        }

        $query->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc');

        $categories = $query->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Categories retrieved successfully',
            'data' => $categories,
        ], 200);
    }

    /**
     * Get a limited number of top/featured categories (e.g., for homepage).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function topCategories(Request $request)
    {
        $limit = $request->input('limit', 6); // Default to 6, can be overridden by query param
        if (!is_numeric($limit) || $limit <= 0) {
            $limit = 6; // Fallback to default if invalid limit is provided
        }

        $categories = Category::active()
            ->featuredOnHomepage() // Prefer categories marked for homepage
            ->parentCategories()   // Often top categories are parent categories
            ->with('children')     // Eager load children for hierarchical display
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->limit($limit)
            ->get();

        if ($categories->isEmpty()) {
            // Fallback if not enough homepage categories or none exist
            $categories = Category::active()
                ->parentCategories()
                ->with('children')
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->limit($limit)
                ->get();
        }


        return response()->json([
            'status' => 'success',
            'message' => "Top {$limit} categories retrieved successfully",
            'data' => $categories,
        ], 200);
    }


    /**
     * Display the specified category, serving as a 'category page' API.
     * It includes the category's direct children and parent.
     *
     * @param  string  $slug The category slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($slug)
    {
        $category = Category::active()
            ->where('slug', $slug)
            ->with(['parent', 'children' => function ($query) {
                $query->active()->orderBy('sort_order')->orderBy('name');
            }])
            ->first();

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found or is not active',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Category data for page retrieved successfully',
            'data' => $category,
        ], 200);
    }
}
