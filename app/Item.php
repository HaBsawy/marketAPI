<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'user_id', 'sub_cat_id', 'price', 'stock', 'brand', 'description', 'expiration_date', 'min_allowed_stock',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function subCategory() {
        return $this->belongsTo(SubCategory::class, 'sub_cat_id');
    }

    public function images() {
        return $this->hasMany(Image::class);
    }

    public function checkouts() {
        return $this->belongsToMany(Checkout::class, 'checkout_items', 'item_id');
    }
}
