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
            'password' => $this->password,
            'role' => $this->role,
            'href' => [
                'categories' => ($this->categories->count() !== 0) ? route('userCategories', $this->id) : 'there is no categories',
                'subcategories' => ($this->subCategories->count() !== 0) ? route('userSubCategory', $this->id) : 'there is no subcategories',
                'items' => ($this->items->count() !== 0) ? route('userItem', $this->id) : 'there is no items',
                'checkouts' => ($this->checkouts->count() !== 0) ? route('userCheckouts', $this->id) : 'there is no checkouts',
            ]
        ];
    }
}
