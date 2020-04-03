<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'user_id' => \App\User::where('role', '!=', 'custome')->get()->random(),
        'sub_cat_id' => \App\SubCategory::all()->random(),
        'price' => $faker->randomFloat(2, 1,1000),
        'stock' => $faker->numberBetween(1,1000),
        'brand' => $faker->company,
        'description' => $faker->text(300),
        'expiration_date' => $faker->date('Y-m-d'),
        'min_allowed_stock' => $faker->randomNumber(10-20),
    ];
});
