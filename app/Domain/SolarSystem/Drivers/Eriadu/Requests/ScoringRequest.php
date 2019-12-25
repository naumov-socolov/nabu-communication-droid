<?php

namespace App\Domain\SolarSystem\Drivers\Eriadu\Requests;

use App\Domain\SolarSystem\Contracts\DriverRequestAbstract;
use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Models\Message;
use GuzzleHttp\Psr7\Response;

class ScoringRequest extends DriverRequestAbstract
{
    const REQUEST_NAME = 'SCORING';

    /**
     * @param Message $message
     * @param array   $additionalData
     *
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
            'user_created' => $message->user->created_at->format("Y-m-d"),
            'rank' => $message->user->rank,
            'origin' => $message->user->origin,
            'status_code' => 1,
            'hash_key' => $additionalData['hash_key'],
            'proceed_link' => 'https://starwars.fandom.com/wiki/Eriadu',
        ];
    }

    /**
     * @param array $requestData
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function performRequest($requestData): Response
    {
        return $this->client->request(
            'POST',
            $this->config('scoring_path'),
            [
                'form_params' => $requestData,
                'timeout' => $this->config('scorring_timeout'),
            ]
        );
    }

    /**
     * @param Response $response
     * @return SolarSystemResponseContract
     */
    public function processResponse($response): SolarSystemResponseContract
    {
        $solarSystemResponse = $this->fillBasicResponse($response);
        $content = json_decode($solarSystemResponse->responseContent)->form;

        switch ($content->status_code) {
            case -1:
                $solarSystemResponse->makeDataError($this->config('data_error_expires_at'));
                break;
            case 0:
                $solarSystemResponse->makeCancelled($this->config('cancelled_expires_at'));
                break;
            case 1:
                $solarSystemResponse->setHashKey($content->hash_key);
                $solarSystemResponse->setProceedLink($content->proceed_link);
                $solarSystemResponse->makeApproved($this->config('approved_expires_at'));
                break;
            default:
                $solarSystemResponse->makeServerError($this->config('server_error_expires_at'));
                break;
        }

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
