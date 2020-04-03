<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user_name' => $this->user->name,
            'name' => $this->name,
            'href' => [
                'subcategories' => ($this->subCategories->count() !== 0) ? route('categorySubCategory', $this->id) : 'there is no subcategories',
            ]
        ];
    }
}
