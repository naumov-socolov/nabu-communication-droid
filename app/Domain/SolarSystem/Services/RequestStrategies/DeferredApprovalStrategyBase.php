<?php

namespace App\Domain\SolarSystem\Services\RequestStrategies;

use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Contracts\Services\BaseRequestStrategy;
use App\Domain\SolarSystem\Contracts\Services\RequestStrategyContract;
use App\Domain\SolarSystem\Models\Message;

class DeferredApprovalStrategyBase extends BaseRequestStrategy implements RequestStrategyContract
{
    /**
     * @return Message
     */
    public function handle(): Message
    {
        $solarSystemResponse =
            $this->assignedRequestHandlers[self::FAST_APPROVAL]
                ->handle();

        $this->dispatchGetDeferredApprovalStatus($solarSystemResponse);
        $this->dispatchGetDeferredIssuedStatus();

        return $this->messageUpdater->update($solarSystemResponse);
    }

    /**
     * @param SolarSystemResponseContract $response
     */
    private function dispatchGetDeferredApprovalStatus(SolarSystemResponseContract $response)
    {
        if (!$response->isPending()) {
            return;
        }

        dispatch($this->assignedDeferredJobs[self::DEFERRED_APPROVAL])
            ->onQueue(self::QUEUE_NAME);
    }

    private function dispatchGetDeferredIssuedStatus()
    {
        dispatch($this->assignedDeferredJobs[self::DEFERRED_ISSUE])
            ->delay(now()->addDay())
            ->onQueue(self::QUEUE_NAME);
    }
}
