<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CheckoutItems;
use Faker\Generator as Faker;

$factory->define(CheckoutItems::class, function (Faker $faker) {
    return [
        'checkout_id' => \App\Checkout::all()->random(),
        'item_id' => \App\Item::all()->random(),
    ];
});
