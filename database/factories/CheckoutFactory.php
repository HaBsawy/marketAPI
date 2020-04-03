<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Checkout;
use Faker\Generator as Faker;

$factory->define(Checkout::class, function (Faker $faker) {
    return [
        'user_id' => \App\User::where('role', 'custome')->get()->random(),
        'status' => $faker->randomElement(['prepared', 'sent', 'delivered & paid']),
    ];
});
