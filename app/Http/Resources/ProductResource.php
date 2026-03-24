<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'slug'               => $this->slug,
            'sku'                => $this->sku,
            'price'              => (float) $this->effective_price,
            'regular_price'      => (float) $this->regular_price,
            'discount_percent'   => $this->discount_percentage,
            'savings'            => (float) $this->savings_amount,
            'stock'              => $this->current_stock,
            'thumbnail'          => $this->thumbnail_url,
            'short_description'  => $this->short_description,
            'long_description'   => $this->long_description,
            'images'             => $this->images->map(fn($img) => asset('storage/' . $img->image_path)),
            'variants'           => ProductVariantResource::collection($this->whenLoaded('variants')),
            'sections'           => ProductPageSectionResource::collection($this->whenLoaded('pageSections')),
            'meta' => [
                'title'       => $this->meta_title ?? $this->name,
                'description' => $this->meta_description,
                'og_image'    => $this->og_image_path ? asset('storage/' . $this->og_image_path) : $this->thumbnail_url,
            ]
        ];
    }
}
