<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckoutItems extends Model
{
    protected $fillable = [
        'checkout_id', 'item_id'
    ];
}
