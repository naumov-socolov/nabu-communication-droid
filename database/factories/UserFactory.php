<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Users\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$factory->define(
    User::class,
    function (Faker $faker) {
        return [
            'uuid' => rand(),
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => Hash::make(Str::random(32)),
            'remember_token' => Str::random(10),
            'rank' => $faker->companySuffix,
            'origin' => $faker->colorName,
            'duties' => [$faker->word],
            'position' => $faker->word,
        ];
    }
);
