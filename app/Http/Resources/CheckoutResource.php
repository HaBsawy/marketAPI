<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutResource extends JsonResource
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
            'status' => $this->status,
            'href' => [
                'items' => ($this->items->count() !== 0) ? route('checkoutItem', $this->id) : 'there is no items',
            ]
        ];
    }
}
