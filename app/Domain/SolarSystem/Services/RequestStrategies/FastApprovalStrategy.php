<?php

namespace App\Domain\SolarSystem\Services\RequestStrategies;

use App\Domain\SolarSystem\Contracts\Services\BaseRequestStrategy;
use App\Domain\SolarSystem\Contracts\Services\RequestStrategyContract;
use App\Domain\SolarSystem\Models\Message;

class FastApprovalStrategy extends BaseRequestStrategy implements RequestStrategyContract
{
    //use BaseRequestStrategy;

    /**
     * @return Message
     */
    public function handle(): Message
    {
        $solarSystemResponse =
            $this->assignedRequestHandlers[self::FAST_APPROVAL]
                ->handle();

        return $this->messageUpdater->update($solarSystemResponse);
    }
}
