<?php

namespace App\Domain\SolarSystem\Drivers\Corellia\Requests;

use App\Domain\SolarSystem\Contracts\DriverRequestAbstract;
use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Models\Message;
use App\Domain\Users\Models\User;
use GuzzleHttp\Psr7\Response;

class ScoringRequest extends DriverRequestAbstract
{
    public const REQUEST_NAME = 'SCORING';

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
            'user_id' => $message->user->uuid,
            'user_key' => $this->getUserKey($message->user),
            'statusCode' => 1,
            'hash_key' => $message->hash_key ?? $additionalData['hash_key'],
            'url' => 'https://starwars.fandom.com/wiki/Corellia',
            'message_id' => $message->id,
            'status' => $additionalData['status'],
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
                'timeout' => $this->config('scoring_timeout'),
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

        $solarSystemResponse->setHashKey($content->hash_key);

        switch ($content->status) {
            case 'pending':
                $solarSystemResponse->makePending($this->config('pending_expires_at'));
                break;
            case 'approved':
                $solarSystemResponse->setProceedLink($content->url);
                $solarSystemResponse->makeApproved($this->config('approved_expires_at'));
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
        $solarSystemResponse = $this->fillBasicException($exception);

        switch ($exception->getCode()) {
            case 400:
            case 404:
                $solarSystemResponse->makeDataError($this->config('data_error_expires_at'));
                break;
            case 410:
            case 422:
                $solarSystemResponse->makeCancelled($this->config('cancelled_expires_at'));
                break;
            case 500:
            case 503:
            default:
                $solarSystemResponse->makeServerError($this->config('server_error_expires_at'));
                break;
        }

        return $solarSystemResponse;
    }

    /**
     * @param User $user
     * @return string
     */
    protected function getUserKey(User $user): string
    {
        return 'keypass:' . rand() . $user->uuid;
    }
}
