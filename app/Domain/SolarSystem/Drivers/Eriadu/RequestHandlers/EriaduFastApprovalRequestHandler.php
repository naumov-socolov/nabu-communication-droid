<?php

namespace App\Domain\SolarSystem\Drivers\Eriadu\RequestHandlers;

use App\Domain\SolarSystem\Drivers\BaseRequestHandler;
use App\Domain\SolarSystem\Traits\GuzzleClient;
use App\Domain\SolarSystem\Contracts\RequestHandlerContract;
use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Drivers\Eriadu\Requests\ScoringRequest;
use App\Domain\SolarSystem\Drivers\Eriadu\Requests\ShakeHandsRequest;
use App\Domain\SolarSystem\Models\Message;

class EriaduFastApprovalRequestHandler extends BaseRequestHandler implements RequestHandlerContract
{
    use GuzzleClient;

    /**
     * @return SolarSystemResponseContract
     */
    public function handle(): SolarSystemResponseContract
    {
        $this->additionalData['request_time'] = now();

        $SolarSystemFastCheckResponse = $this->makeRequest(
            resolve(
                ShakeHandsRequest::class,
                [
                    'client' => $this->client,
                    'configKey' => $this->configKey,
                    'message' => $this->message,
                    'additionalData' => $this->additionalData,
                ]
            )
        );

        if ($SolarSystemFastCheckResponse->status !== Message::PENDING) {
            return $SolarSystemFastCheckResponse;
        }

        $this->additionalData['hash_key'] = $SolarSystemFastCheckResponse->hashKey;

        $solarSystemScoringResponse = $this->makeRequest(
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

        $SolarSystemFastCheckResponse->setHashKey($SolarSystemFastCheckResponse->hashKey);

        return $solarSystemScoringResponse;
    }
}
