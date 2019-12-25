<?php

namespace App\Domain\SolarSystem\Contracts\Services;

use App\Domain\SolarSystem\Contracts\Jobs\DeferredJobContract;
use App\Domain\SolarSystem\Contracts\RequestHandlerContract;
use App\Domain\SolarSystem\Contracts\MessageUpdaterContract;

class BaseRequestStrategy
{
    protected $assignedRequestHandlers;

    protected $assignedDeferredJobs;

    protected $messageUpdater;

    /**
     * @param MessageUpdaterContract $messageUpdater
     */
    public function __construct(
        MessageUpdaterContract $messageUpdater
    ) {
        $this->messageUpdater = $messageUpdater;
    }

    /**
     * @param array $drivers
     * @return BaseRequestStrategy
     */
    public function assignRequestHandlers(array $drivers): self
    {
        foreach ($drivers as $type => $handler) {
            if (in_array($type, RequestStrategyContract::APPROPRIATE_TYPES)) {
                $this->assignRequestHandler($type, $handler);
            }
        }

        return $this;
    }

    /**
     * @param string                 $type
     * @param RequestHandlerContract $handler
     */
    private function assignRequestHandler(string $type, RequestHandlerContract $handler)
    {
        $this->assignedRequestHandlers[$type] = $handler;
    }

    /**
     * @param array $drivers
     * @return BaseRequestStrategy
     */
    public function assignDeferredJobs(array $drivers): self
    {
        foreach ($drivers as $type => $handler) {
            if (in_array($type, RequestStrategyContract::APPROPRIATE_TYPES)) {
                $this->assignJob($type, $handler);
            }
        }

        return $this;
    }

    /**
     * @param string              $type
     * @param DeferredJobContract $job
     */
    private function assignJob(string $type, DeferredJobContract $job)
    {
        $this->assignedDeferredJobs[$type] = $job;
    }
}
