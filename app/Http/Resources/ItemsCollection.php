<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemsCollection extends JsonResource
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
            'subcategory' => $this->subCategory->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'brand' => $this->brand,
            'description' => $this->description,
            'expiration_date' => $this->expiration_date,
            'min_allowed_stock' => $this->min_allowed_stock,
            'href' => [
                'item' => route('items.show', $this->id),
            ]
        ];
    }
}
