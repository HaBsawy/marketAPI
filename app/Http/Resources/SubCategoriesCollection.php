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
            'user' => $this->user->name,
            'category' => $this->category->name,
            'name' => $this->name,
            'href' => [
                'subcategory' => route('subcategories.show', $this->id),
            ],
        ];
    }
}
