<?php

namespace App\Domain\SolarSystem\Drivers\Corellia\RequestHandlers;

use App\Domain\SolarSystem\Contracts\RequestHandlerContract;
use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Drivers\BaseRequestHandler;
use App\Domain\SolarSystem\Drivers\Corellia\Requests\ScoringRequest;
use App\Domain\SolarSystem\Traits\guzzleClient;

class CorelliaDeferredApprovalRequestHandler extends BaseRequestHandler implements RequestHandlerContract
{
    use GuzzleClient;

    /**
     * @return SolarSystemResponseContract
     */
    public function handle(): SolarSystemResponseContract
    {
        $this->additionalData['status'] = 'approved';
        $this->additionalData['request_signed'] = now()->subDays(15);

        $solarSystemResponse = $this->makeRequest(
            resolve(
                ScoringRequest::class,
                [
                    'client' => $this->client,
                    'configKey' => $this->configKey,
                    'message' => $this->message,
                    'additionalData' => $this->additionalData,
                ]
            )
        );
        $solarSystemResponse->setHashKey($this->message->hash_key);

        return $solarSystemResponse;
    }
}
