<?php

namespace App\Domain\SolarSystem\Contracts;

use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Models\Message;

interface RequestHandlerContract
{
    /**
     * @param LoggerContract $logger
     * @param string         $configKey
     * @param Message        $message
     * @param array          $additionalData
     */
    public function __construct(LoggerContract $logger, string $configKey, Message $message, array $additionalData);

    /*
     * handle method creates all necessary request objects
     * executes all the requests
     * and returns universal SolarSystemResponseContract
     *
     * @return SolarSystemResponseContract
     */
    public function handle(): SolarSystemResponseContract;

    /*
     * makeRequest method executes specified query,
     * logs result
     * and returns universal SolarSystemResponseContract
     *
     * @param DriverRequestAbstract $driverRequest
     * @return SolarSystemResponseContract
     */
    public function makeRequest(
        DriverRequestAbstract $driverRequest
    ): SolarSystemResponseContract;

    /**
     * initClient method that initializes used HTTP clients with base params.
     * Desired HTTP client implementation is attached as a "trait" to specific RequestHandler class.
     *
     * @param string $basePath
     */
    public function initClient(string $basePath);
}
