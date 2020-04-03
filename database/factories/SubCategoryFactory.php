<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SubCategory;
use Faker\Generator as Faker;

$factory->define(SubCategory::class, function (Faker $faker) {
    return [
        'user_id' => \App\User::where('role', 'admin')->get()->random(),
        'category_id' => \App\Category::all()->random(),
        'name' => $faker->company,
    ];
});
