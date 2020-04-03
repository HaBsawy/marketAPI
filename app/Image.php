<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'item_id', 'name',
    ];

    public function item() {
        return $this->belongsTo(Item::class);
    }
}
