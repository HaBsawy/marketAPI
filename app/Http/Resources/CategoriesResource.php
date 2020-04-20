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
            'name' => $this->name,
            'user' => $this->user->name,
            'subcategories' => [
                'href' => ($this->subCategories->count() !== 0) ? route('categorySubCategory', $this->id) : 'there is no subcategories',
                'method' => 'GET'
            ]
        ];
    }
}
