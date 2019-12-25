<?php

namespace App\Domain\SolarSystem\Contracts;

use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;

interface LoggerContract
{
    /*
     * Class that implements logging requests to Outer Rim Solar Systems
     *
     * Uses SolarSystemResponseContract as DataTransferObject
     * that stores all necessary data: request name, request data and response data
     *
     * @param SolarSystemResponseContract $response
     */
    public function log(SolarSystemResponseContract $response);
}
