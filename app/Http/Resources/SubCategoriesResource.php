<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoriesResource extends JsonResource
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
            'category' => $this->category->name,
            'href' => [
                'items' => ($this->items->count() !== 0) ? route('subcategoryItem', $this->id) : 'there is no items',
            ],
        ];
    }
}
