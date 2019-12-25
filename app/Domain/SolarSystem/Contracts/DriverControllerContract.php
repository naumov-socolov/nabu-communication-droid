<?php

namespace App\Domain\SolarSystem\Contracts;

use App\Domain\SolarSystem\Models\Message;

interface DriverControllerContract
{
    /**
     * @param Message $message
     * @param array   $additionalData
     */
    public function __construct(Message $message, array $additionalData);

    public function handle();
}
