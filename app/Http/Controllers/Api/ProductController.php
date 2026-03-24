<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get all active products with pagination
     */
    public function index()
    {
        $products = Product::active()
            ->latest()
            ->paginate(12);

        return ProductResource::collection($products);
    }

    /**
     * Get only featured products for the landing page component
     */
    public function featured()
    {
        $products = Product::active()
            ->where('is_featured', true)
            ->limit(8)
            ->get();

        return ProductResource::collection($products);
    }

    /**
     * Get a single product by slug
     */
    public function show($slug)
    {
        $product = Product::active()
            ->with([
                'images',
                'variants.attributeValues',
                'brand',
                'pageSections' => function ($query) {
                    $query->where('is_active', true)->orderBy('sort_order');
                },
                'pageSections.sectionable' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        \App\Models\Section\BenefitSection::class      => ['items'],
                        \App\Models\Section\ComparisonSection::class   => ['items'],
                        \App\Models\Section\FeatureCardSection::class  => ['cards'],
                        \App\Models\Section\MediaNewsSection::class    => ['videos'],
                        \App\Models\Section\SocialProofSection::class  => ['items'],
                        \App\Models\Section\UsageSection::class         => ['items'],
                    ]);
                }
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        return new ProductResource($product);
    }
}
