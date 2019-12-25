<?php

namespace App\Domain\SolarSystem\Drivers\Corellia\Jobs;

use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Contracts\Jobs\DeferredJobAbstract;

class CorelliaDeferredIssuedJob extends DeferredJobAbstract
{
    const DELAY = 60 * 60; // one hour delay

    public $tries = 24 * 7; // try to get issued status in a week since request is approved

    public function handle()
    {
        if (!$this->approvedWithHashKey()) {
            return false;
        }

        $solarSystemResponse = $this->makeRequest();

        // if request still in pending status, we should be patient
        if ($solarSystemResponse->isPending()) {
            return $this->releaseOrExit();
        }

        // getting issue status, if there is final status - we update the message and returning
        // if status is not final - trying to get it one more time
        return $this->processResponse($solarSystemResponse);
    }

    protected function releaseOrExit()
    {
        if ($this->isMaxAttemptsExceeded()) {
            return false;
        }

        return $this->release(self::DELAY);
    }

    protected function isFinalStatus(SolarSystemResponseContract $solarSystemResponse): bool
    {
        return $solarSystemResponse->isCancelled()
               || $solarSystemResponse->isRejected()
               || $solarSystemResponse->isIssued();
    }

    /**
     * @return SolarSystemResponseContract
     */
    protected function makeRequest(): SolarSystemResponseContract
    {
        return resolve(
            $this->requestHandlers[self::DEFERRED_ISSUE]['class'],
            $this->requestHandlers[self::DEFERRED_ISSUE]['params']
        )
            ->handle();
    }

    /**
     * @param SolarSystemResponseContract $solarSystemResponse
     * @return bool|void
     */
    protected function processResponse(SolarSystemResponseContract $solarSystemResponse)
    {
        if ($this->isFinalStatus($solarSystemResponse)) {
            $this->messageUpdater->update($solarSystemResponse);

            return false;
        }

        if ($this->isMaxAttemptsExceeded()) {
            $solarSystemResponse->makeRejected($this->config('rejected_expires_at'));
            $this->messageUpdater->update($solarSystemResponse);

            return false;
        }

        return $this->release(self::DELAY);
    }
}
