<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoriesCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'category' => $this->category->name,
            'user' => $this->user->name,
            'subcategory' => [
                'href' => route('subcategories.show', $this->id),
                'method' => 'GET'
            ],
        ];
    }
}
