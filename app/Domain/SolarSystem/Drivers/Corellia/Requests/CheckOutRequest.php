<?php

namespace App\Domain\SolarSystem\Drivers\Corellia\Requests;

use App\Domain\SolarSystem\Contracts\DriverRequestAbstract;
use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Models\Message;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

class CheckOutRequest extends DriverRequestAbstract
{
    public const REQUEST_NAME = 'CHECKOUT';

    /**
     * @param Message $message
     * @param array   $additionalData
     * @return array
     */
    public function prepareRequest(Message $message, array $additionalData): array
    {
        return [
            'hash_key' => $message->hash_key,
            'status' => 'issued',
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
        $content = json_decode($solarSystemResponse->responseContent);

        switch ($content->form->status) {
            case "pending":
            case "approved":
                $solarSystemResponse->makeApproved($this->config('approved_expires_at'));
                break;
            case "canceled":
                $solarSystemResponse->makeRejected($this->config('rejected_expires_at'));
                break;
            case "rejected":
                $solarSystemResponse->makeCancelled($this->config('cancelled_expires_at'));
                break;
            case "issued":
                $solarSystemResponse->makeIssued($this->config('issued_expires_at'));
                break;
            default:
                $solarSystemResponse->makePending($this->config('pending_expires_at'));
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
            case 406:
                $solarSystemResponse->makePending($this->config('pending_expires_at'));
                break;
            default:
                $solarSystemResponse->makeServerError($this->config('server_error_expires_at'));
                break;
        }

        return $solarSystemResponse;
    }
}
