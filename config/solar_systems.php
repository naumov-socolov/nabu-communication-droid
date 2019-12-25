<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Integration with Outer Rim Solar Systems
    |--------------------------------------------------------------------------
    |
    | This file contains connection settings for each Solar System trade interface
    | Any Solar System may have its own config configuration, so feel free
    | to add and modify settings as you need to.
    |
    */
    'eriadu' => [
        'driver_class' => App\Domain\SolarSystem\Drivers\Eriadu\EriaduController::class,
        'host' => 'https://httpbin.org',
        'shake_hands_path' => '/anything',
        'shake_hands_timeout' => 60.0,
        'scoring_path' => '/anything',
        'scoring_timeout' => 60.0,
        'secret_key' => '1243124',
        'password' => 'rebellion_FDAFD_2148',
        'pending_expires_at' => 60 * 60 * 4,
        'cancelled_expires_at' => 60 * 60 * 24 * 21,
        'approved_expires_at' => 60 * 60 * 24 * 7,
        'waiting_expires_at' => 5 * 60,
        'data_error_expires_at' => 5 * 60,
        'server_error_expires_at' => 5 * 60,
    ],
    'corellia' => [
        'driver_class' => App\Domain\SolarSystem\Drivers\Corellia\CorelliaController::class,
        'host' => 'https://httpbin.org',
        'shake_hands_path' => '/anything',
        'scoring_path' => '/anything',
        'shake_hands_timeout' => 60.0,
        'scoring_timeout' => 60.0,
        'gate_number' => 346,
        'token' => 'FFDFRRCorellia',
        'cancelled_expires_at' => 60 * 60 * 24 * 21,
        'pending_expires_at' => 60 * 60 * 4,
        'issued_expires_at' => 60 * 60 * 24 * 30,
        'rejected_expires_at' => 60 * 60 * 24 * 30,
        'approved_expires_at' => 60 * 60 * 24 * 30,
        'waiting_expires_at' => 5 * 60,
        'data_error_expires_at' => 5 * 60,
        'server_error_expires_at' => 5 * 60,
    ],
];
