<?php

namespace App\Domain\SolarSystem\Drivers\Corellia\RequestHandlers;

use App\Domain\SolarSystem\Contracts\RequestHandlerContract;
use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Drivers\BaseRequestHandler;
use App\Domain\SolarSystem\Drivers\Corellia\Requests\CheckOutRequest;
use App\Domain\SolarSystem\Traits\guzzleClient;

class CorelliaDeferredIssuedRequestHandler extends BaseRequestHandler implements RequestHandlerContract
{
    use GuzzleClient;

    /**
     * @return SolarSystemResponseContract
     */
    public function handle(): SolarSystemResponseContract
    {
        $solarSystemResponse = $this->makeRequest(
            resolve(
                CheckOutRequest::class,
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
