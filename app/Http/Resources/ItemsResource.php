<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemsResource extends JsonResource
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
            'user' => $this->user->name,
            'subCategory' => $this->subCategory->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'brand' => $this->brand,
            'description' => $this->description,
            'expiration_date' => $this->expiration_date,
            'min_allowed_stock' => $this->min_allowed_stock,
            'href' => [
                'images' => 'images link',
                'checkouts' => 'checkouts link'
            ]
        ];
    }
}
