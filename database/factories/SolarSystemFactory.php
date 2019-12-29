<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\SolarSystem\Models\SolarSystem;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$connectedDrivers = ['eriadu', 'corellia'];
$connectedImages = [
    'https://res.cloudinary.com/drmyhljip/image/upload/v1577627240/nabu_communication_droid/solar_systems/eriadu_hbmovh.svg',
    'https://res.cloudinary.com/drmyhljip/image/upload/v1577627240/nabu_communication_droid/solar_systems/corellia_maviol.svg',
];

$factory->define(
    SolarSystem::class,
    function(Faker $faker) use ($connectedDrivers, $connectedImages) {
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
            'driver_name' => $connectedDrivers[rand(0, count($connectedDrivers) - 1)],
            'img' => $connectedImages[rand(0, count($connectedImages) - 1)],
        ];
    }
);
