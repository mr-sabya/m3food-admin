<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'price' => (float) $this->price,
            'stock' => $this->quantity,
            'display_name' => $this->display_name, // Accessor from your model
            'attributes' => $this->attributeValues->map(fn($v) => [
                'name' => $v->attribute->name, // Assuming AttributeValue belongs to Attribute
                'value' => $v->value
            ]),
        ];
    }
}
