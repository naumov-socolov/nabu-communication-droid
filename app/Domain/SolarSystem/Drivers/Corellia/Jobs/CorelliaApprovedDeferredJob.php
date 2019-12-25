<?php

namespace App\Domain\SolarSystem\Drivers\Corellia\Jobs;

use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Contracts\Jobs\DeferredJobAbstract;
use App\Domain\SolarSystem\Events\SolarSystemDeferredApprovalReceived;

class CorelliaApprovedDeferredJob extends DeferredJobAbstract
{
    const DELAY = 15;

    public $tries = 40; // 15 * 40sec = 10min

    public function handle()
    {
        if (!$this->approvedWithHashKey()) {
            return;
        }

        $solarSystemResponse = $this->makeRequest();

        if (!$this->isFinalStatus($solarSystemResponse)) {
            return $this->releaseOrExit($solarSystemResponse);
        }

        $this->updateMessageAndNotifyUser($solarSystemResponse);
    }

    /**
     * @return SolarSystemResponseContract
     */
    protected function makeRequest(): SolarSystemResponseContract
    {
        return resolve(
            $this->requestHandlers[self::DEFERRED_APPROVAL]['class'],
            $this->requestHandlers[self::DEFERRED_APPROVAL]['params']
        )
            ->handle();
    }

    /**
     * @param SolarSystemResponseContract $solarSystemResponse
     * @return bool
     */
    protected function isFinalStatus(SolarSystemResponseContract $solarSystemResponse): bool
    {
        return $solarSystemResponse->isApproved() || $solarSystemResponse->isCancelled();
    }

    /**
     * @param SolarSystemResponseContract|null $solarSystemResponse
     * @return bool|void
     */
    protected function releaseOrExit(SolarSystemResponseContract $solarSystemResponse = null)
    {
        if ($this->isMaxAttemptsExceeded()) {
            $solarSystemResponse->makeCancelled($this->config('cancelled_expires_at'));
            $this->updateMessageAndNotifyUser($solarSystemResponse);

            return false;
        }

        return $this->release(self::DELAY);
    }

    /**
     * @param SolarSystemResponseContract $solarSystemResponse
     */
    protected function updateMessageAndNotifyUser(SolarSystemResponseContract $solarSystemResponse)
    {
        $message = $this->messageUpdater->update($solarSystemResponse);
        event(
            new SolarSystemDeferredApprovalReceived(
                $message,
                $message->user,
                $message->solarSystem
            )
        );
    }
}
