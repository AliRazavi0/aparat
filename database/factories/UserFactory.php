<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'type' => User::TYPE_USER,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$yHOVJzIYR5NRsj1JVFrKIuJ8X4JZHlW7Y7QAgRPpnd4MEp9uglwHK', // 123456
        'mobile' => '+989' . random_int(1111, 9999) . random_int(11111, 99999),
        'avatar' => null,
        'website' => $faker->url,
        'verify_code' => null,
        'verified_at' => now(),
    ];
});
