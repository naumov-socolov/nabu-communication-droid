<?php

namespace App\Domain\SolarSystem\Drivers;

use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Contracts\DriverRequestAbstract;
use App\Domain\SolarSystem\Contracts\LoggerContract;
use App\Domain\SolarSystem\Models\Message;
use Illuminate\Support\Facades\Config;

class BaseRequestHandler
{
    protected $logger;

    protected $configKey;

    protected $message;

    protected $additionalData;

    /**
     * @param LoggerContract $logger
     * @param string         $configKey
     * @param Message        $message
     * @param array          $additionalData
     */
    public function __construct(LoggerContract $logger, string $configKey, Message $message, array $additionalData)
    {
        $this->logger = $logger;
        $this->configKey = $configKey;
        $this->message = $message;
        $this->additionalData = $additionalData;

        $this->initClient(Config::get("{$this->configKey}.host"));
    }

    public function makeRequest(
        DriverRequestAbstract $driverRequest
    ): SolarSystemResponseContract {
        $solarSystemResponse = $driverRequest->handle();

        $this->logger->log($solarSystemResponse);

        return $solarSystemResponse;
    }
}
