<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\SolarSystem\Models\Message;
use App\Domain\SolarSystem\Models\RequestLog;
use Faker\Generator as Faker;

$factory->define(
    RequestLog::class,
    function (Faker $faker) {
        $random_request_names = ['SHAKE_HANDS', 'SCORING', 'CHECKOUT'];

        $statuses = [
            Message::APPROVED,
            Message::CANCELLED,
            Message::SERVER_ERROR,
            Message::PENDING,
            Message::ISSUED,
            Message::REJECTED,
        ];

        return [
            'message_id' => rand(0, 10),
            'request_name' => $random_request_names[array_rand($random_request_names)],
            'request_data' => $faker->text,
            'response_data' => $faker->text,
            'status' => $statuses[array_rand($statuses)],
        ];
    }
);
