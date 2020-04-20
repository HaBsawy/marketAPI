<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'role' => $this->role,
            'categories' => [
                'href' => ($this->categories->count() !== 0) ? route('userCategories', $this->id) : 'there is no categories',
                'method' => 'GET'
            ],
            'subcategories' => [
                'href' => ($this->subCategories->count() !== 0) ? route('userSubCategory', $this->id) : 'there is no subcategories',
                'method' => 'GET'
            ],
            'items' => [
                'href' => ($this->items->count() !== 0) ? route('userItem', $this->id) : 'there is no items',
                'method' => 'GET'
            ],
            'checkouts' => [
                'href' => ($this->checkouts->count() !== 0) ? route('userCheckouts', $this->id) : 'there is no checkouts',
                'method' => 'GET'
            ],
        ];
    }
}
