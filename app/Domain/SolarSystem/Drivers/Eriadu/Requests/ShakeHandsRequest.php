<?php

namespace App\Domain\SolarSystem\Drivers\Eriadu\Requests;

use App\Domain\SolarSystem\Contracts\DriverRequestAbstract;
use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Models\Message;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

class ShakeHandsRequest extends DriverRequestAbstract
{
    const REQUEST_NAME = 'SHAKE_HANDS';

    /**
     * @param Message $message
     * @param array   $additionalData
     * @return array
     */
    public function prepareRequest(Message $message, array $additionalData): array
    {
        return [
            'timestamp' => $additionalData['request_time'],
            'secret_key' => $this->config('secret_key'),
            'password' => sha1($this->config('password') . $additionalData['request_time']),
            'username' => $message->user->name,
            'user_id' => $message->user->uuid,
            'statusCode' => 1,
            'hash_key' => rand(),
        ];
    }

    /**
     * @param array $requestData
     * @return Response
     * @throws GuzzleException
     */
    public function performRequest(array $requestData): Response
    {
        return $this->client->request(
            'POST',
            $this->config('shake_hands_path'),
            [
                'form_params' => $requestData,
                'timeout' => $this->config('shake_hands_timeout'),
            ]
        );
    }

    /**
     * @param Response $response
     * @return SolarSystemResponseContract
     */
    public function processResponse(Response $response): SolarSystemResponseContract
    {
        $solarSystemResponse = $this->fillBasicResponse($response);
        $content = json_decode($solarSystemResponse->responseContent)->form;

        if (!is_object($content)) {
            $solarSystemResponse->makeServerError($this->config('server_error_expires_at'));

            return $solarSystemResponse;
        }

        if ($content->statusCode == -1) {
            $solarSystemResponse->makeDataError($this->config('data_error_expires_at'));

            return $solarSystemResponse;
        }

        if ($content->statusCode == 0) {
            $solarSystemResponse->makeCancelled($this->config('cancelled_expires_at'));

            return $solarSystemResponse;
        }

        if ($content->statusCode == 1 && !empty($content->hash_key)) {
            $solarSystemResponse->setHashKey($content->hash_key);
            $solarSystemResponse->makePending($this->config('pending_expires_at'));

            return $solarSystemResponse;
        }

        $solarSystemResponse->makeServerError($this->config('server_error_expires_at'));

        return $solarSystemResponse;
    }

    /**
     * @param \Exception $exception
     * @return SolarSystemResponseContract
     */
    public function processException(\Exception $exception): SolarSystemResponseContract
    {
        return $this->fillBasicException($exception);
    }
}
