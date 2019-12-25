<?php

namespace App\Domain\SolarSystem\Drivers\Corellia\Requests;

use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Contracts\DriverRequestAbstract;
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
            'user_registration' => $additionalData['request_signed'],
            'gate_number' => $this->config('gate_number'),
            'token' => $this->config('token'),
            'user_activation_date' => $message->user->created_at->format("Y-m-d"),
            'ip' => $additionalData['ip'],
            'email_address' => $this->generateRandomEmail(),
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
    public function performRequest($requestData): Response
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

        if (is_object($content) && !empty($content->hash_key)) {
            $solarSystemResponse->setHashKey($content->hash_key);
            $solarSystemResponse->makePending($this->config('pending_expires_at'));
        } else {
            $solarSystemResponse->makeCancelled($this->config('cancelled_expires_at'));
        }

        return $solarSystemResponse;
    }

    /**
     * @param \Exception $exception
     * @return SolarSystemResponseContract
     */
    public function processException(\Exception $exception): SolarSystemResponseContract
    {
        //dd($exception);
        $solarSystemResponse = $this->fillBasicException($exception);

        switch ($exception->getCode()) {
            case 400:
                $solarSystemResponse->makeDataError($this->config('data_error_expires_at'));
                break;
            case 410:
            case 422:
                $solarSystemResponse->makeCancelled($this->config('cancelled_expires_at'));
                break;
            case 503:
            case 500:
            default:
                $solarSystemResponse->makeServerError($this->config('server_error_expires_at'));
                break;
        }

        return $solarSystemResponse;
    }

    /**
     * @return string
     */
    private function generateRandomEmail(): string
    {
        return 'example.' . microtime(1) . '@corellia.rep';
    }
}
