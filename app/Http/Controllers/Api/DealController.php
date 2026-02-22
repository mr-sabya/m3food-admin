<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Product; // Assuming you have a Product model
use Illuminate\Http\Request;

class DealController extends Controller
{
    /**
     * Get a single featured deal with its associated product.
     * Corresponds to React's 'featuredProduct'.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFeaturedDeal()
    {
        // Find one active, featured deal
        $deal = Deal::active()
            ->featured()
            ->with(['products' => function ($query) {
                // Eager load only one product for the featured deal, if available
                $query->inRandomOrder()->limit(1);
            }])
            ->first();

        if (!$deal) {
            return response()->json([
                'status' => 'error',
                'message' => 'No featured deal found',
                'data' => null,
            ], 404);
        }

        // Format the response to match your React component's 'featuredProduct' structure
        $featuredProductData = null;
        if ($deal->products->isNotEmpty()) {
            $product = $deal->products->first();
            $featuredProductData = [
                'name' => $product->name, // Assuming Product has a 'name' field
                'image' => $product->image_url ?? $deal->banner_image_path, // Product image or deal banner
                'price' => $product->price, // Assuming Product has a 'price' field
                'link' => '/product/' . $product->slug, // Assuming Product has a 'slug' for its page
                'deal_value' => $deal->value, // Include deal value for frontend calculation if needed
                'deal_type' => $deal->type,   // e.g., 'percentage', 'fixed'
            ];
        } else {
            // If no product is directly attached to the featured deal, use deal's own info
            $featuredProductData = [
                'name' => $deal->name,
                'image' => $deal->banner_image_url,
                'price' => null, // No product price if no product attached
                'link' => $deal->link_target ?? '#',
                'deal_value' => $deal->value,
                'deal_type' => $deal->type,
            ];
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Featured deal retrieved successfully',
            'data' => $featuredProductData,
        ], 200);
    }

    /**
     * Get a list of "best deals" with their associated products.
     * Corresponds to React's 'bestProducts'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBestDeals(Request $request)
    {
        $limit = $request->input('limit', 4); // Default to 4, can be overridden
        if (!is_numeric($limit) || $limit <= 0) {
            $limit = 4; // Fallback
        }

        // Fetch active deals, excluding any featured ones (to avoid duplication with getFeaturedDeal)
        $deals = Deal::active()
            ->where('is_featured', false) // Don't include featured deals here
            ->with(['products' => function ($query) {
                // Eager load only one product for each deal, if available
                $query->inRandomOrder()->limit(1);
            }])
            ->orderBy('display_order', 'asc') // Or order by 'id', 'created_at', etc.
            ->inRandomOrder() // Randomize if display_order isn't strict enough
            ->limit($limit)
            ->get();

        $bestProductsData = $deals->map(function ($deal) {
            $productData = null;
            if ($deal->products->isNotEmpty()) {
                $product = $deal->products->first();
                $productData = [
                    'name' => $product->name,
                    'image' => $product->image_url ?? $deal->banner_image_path,
                    'price' => $product->price,
                    'link' => '/product/' . $product->slug,
                    'deal_value' => $deal->value,
                    'deal_type' => $deal->type,
                ];
            } else {
                // Fallback if deal has no product, use its own info
                $productData = [
                    'name' => $deal->name,
                    'image' => $deal->banner_image_url,
                    'price' => null,
                    'link' => $deal->link_target ?? '#',
                    'deal_value' => $deal->value,
                    'deal_type' => $deal->type,
                ];
            }
            return $productData;
        })->filter()->values(); // Remove any nulls if deals somehow resulted in no product data

        return response()->json([
            'status' => 'success',
            'message' => "Top {$limit} best deals retrieved successfully",
            'data' => $bestProductsData,
        ], 200);
    }
}
