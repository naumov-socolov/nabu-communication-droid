<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\SolarSystem\Models\SolarSystem;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

//const EXISTING_DRIVERS = ['eriadu', 'corellia'];
$existingDrivers = ['eriadu', 'corellia'];

$factory->define(
    SolarSystem::class,
    function (Faker $faker) use ($existingDrivers) {
        return [
            'title' => $faker->userName,
            'slug' => $faker->slug,
            'region' => $faker->colorName,
            'sector' => $faker->hexColor,
            'suns' => [$faker->safeColorName],
            'grid_coordinates' => $faker->latitude
                                  . '/:/'
                                  . $faker->latitude
                                  . '/:/'
                                  . $faker->latitude,
            'trade_routes' => [$faker->company],
            'driver_name' => $existingDrivers[rand(0, count($existingDrivers) - 1)],
            'img' => Str::random(25),
        ];
    }
);
