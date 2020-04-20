<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutCollection extends JsonResource
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
            'status' => $this->status,
            'checkout' => [
                'href' => route('checkouts.show', $this->id),
                'method' => 'GET'
            ]
        ];
    }
}
