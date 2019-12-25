<?php

namespace App\Domain\SolarSystem\Drivers;

use App\Domain\SolarSystem\Contracts\Jobs\DeferredJobContract;
use App\Domain\SolarSystem\Contracts\RequestHandlerContract;
use App\Domain\SolarSystem\Models\Message;
use App\Domain\SolarSystem\Services\DbLogger;
use App\Domain\SolarSystem\Services\MessageUpdater;

class BaseController
{
    const CONFIG_NAME = 'solar_systems';

    protected $message;

    protected $additionalData;

    protected $logger;

    protected $messageUpdaterService;

    protected $configKey;

    /**
     * @param Message $message
     * @param array   $additionalData
     */
    public function __construct(Message $message, array $additionalData)
    {
        $this->message = $message;
        $this->additionalData = $additionalData;

        $this->logger = resolve(DbLogger::class, ['messageId' => $message->id]);
        $this->messageUpdaterService = resolve(MessageUpdater::class, ['message' => $message]);
        $this->configKey = self::CONFIG_NAME . '.' . $this->message->solarSystem->driver_name;
    }

    /**
     * @param string $requestHandlerClass
     * @return RequestHandlerContract
     */
    protected function initRequestHandler(string $requestHandlerClass): RequestHandlerContract
    {
        return resolve(
            $requestHandlerClass,
            [
                'logger' => $this->logger,
                'configKey' => $this->configKey,
                'message' => $this->message,
                'additionalData' => $this->additionalData,
            ]
        );
    }

    /**
     * @param string $deferredJobClass
     * @param array  $requestHandlers
     * @return DeferredJobContract
     */
    protected function initDeferredJob(string $deferredJobClass, array $requestHandlers): DeferredJobContract
    {
        return resolve(
            $deferredJobClass,
            [
                'requestHandlers' => $requestHandlers,
                'configKey' => $this->configKey,
                'messageUpdater' => $this->messageUpdaterService,
            ]
        );
    }

    /**
     * @param string $requestHandlerClass
     * @return array
     */
    protected function composeRequestHandler(string $requestHandlerClass): array
    {
        return [
            'class' => $requestHandlerClass,
            'params' =>
                [
                    'logger' => $this->logger,
                    'configKey' => $this->configKey,
                    'message' => $this->message,
                    'additionalData' => $this->additionalData,
                ],
        ];
    }
}
