<?php

namespace App\Domain\SolarSystem\Drivers\Corellia\RequestHandlers;

use App\Domain\SolarSystem\Contracts\RequestHandlerContract;
use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Drivers\BaseRequestHandler;
use App\Domain\SolarSystem\Drivers\Corellia\Requests\ShakeHandsRequest;
use App\Domain\SolarSystem\Drivers\Corellia\Requests\ScoringRequest;
use App\Domain\SolarSystem\Models\Message;
use App\Domain\SolarSystem\Traits\guzzleClient;

class CorelliaFastApprovalRequestHandler extends BaseRequestHandler implements RequestHandlerContract
{
    use GuzzleClient;

    /**
     * @return SolarSystemResponseContract
     */
    public function handle(): SolarSystemResponseContract
    {
        $this->additionalData['request_signed'] = now()->subSeconds(50);

        $solarSystemShakeHandsResponse = $this->makeRequest(
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

        if ($solarSystemShakeHandsResponse->status !== Message::PENDING) {
            return $solarSystemShakeHandsResponse;
        }

        $this->additionalData['hash_key'] = $solarSystemShakeHandsResponse->hashKey;
        $this->additionalData['message_id'] = $this->message->id;
        $this->additionalData['status'] = 'pending';

        return $this->makeRequest(
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
    }
}
