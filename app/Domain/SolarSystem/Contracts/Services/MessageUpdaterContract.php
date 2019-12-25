<?php

namespace App\Domain\SolarSystem\Contracts;

use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Models\Message;

interface MessageUpdaterContract
{
    /**
     * @param SolarSystemResponseContract $response
     * @return Message
     */
    public function update(SolarSystemResponseContract $response): Message;
}
