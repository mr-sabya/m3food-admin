<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductPageSectionResource extends JsonResource
{
    public function toArray($request): array
    {
        $sectionable = $this->sectionable;

        // This gets the nickname we defined in AppServiceProvider (e.g., 'benefit_section')
        $type = array_search(get_class($sectionable), \Illuminate\Database\Eloquent\Relations\Relation::morphMap());

        return [
            'id'         => $this->id,
            'type'       => $type,
            'sort_order' => $this->sort_order,
            'content'    => $sectionable, // This will include loaded relations like 'items' automatically
        ];
    }
}
