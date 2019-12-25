<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\SolarSystem\Models\Message;
use App\Domain\SolarSystem\Models\SolarSystem;
use App\Domain\Users\Models\User;
use Faker\Generator as Faker;

$factory->define(
    Message::class,
    function (Faker $faker) {
        $statuses = [
            Message::APPROVED,
            Message::CANCELLED,
            Message::SERVER_ERROR,
            Message::PENDING,
            Message::ISSUED,
            Message::REJECTED,
        ];

        return [
            'user_id' => factory(User::class)->create(),
            'solar_system_id' => factory(SolarSystem::class)->create(),
            'status' => $statuses[array_rand($statuses)],
            'amount' => rand(100, 500),
            'proceed_link' => $faker->domainName,
            'hash_key' => $faker->hexColor,
        ];
    }
);
