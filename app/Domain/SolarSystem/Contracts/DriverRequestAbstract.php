<?php

namespace App\Domain\SolarSystem\Contracts;

use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\DataTransferObjects\SolarSystemResponse;
use App\Domain\SolarSystem\Models\Message;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Illuminate\Support\Facades\Config;

abstract class DriverRequestAbstract
{
    protected $client;

    protected $configKey;

    private $message;

    private $additionalData;

    /**
     * @param Client  $client
     * @param string  $configKey
     * @param Message $message
     * @param array   $additionalData
     */
    public function __construct(Client $client, string $configKey, Message $message, array $additionalData)
    {
        $this->client = $client;
        $this->configKey = $configKey;
        $this->message = $message;
        $this->additionalData = $additionalData;
    }

    /*
     * Base method that handles sequence of:
     * - data prepare
     * - request execution
     * - response processing
     * Returns universal SolarSystemResponseContract
     * that contains of: request data, response data and message result
     *
     * @param Message $message
     * @param array   $additionalData
     * @return SolarSystemResponseContract
     */
    public function handle(): SolarSystemResponseContract
    {
        $requestData = $this->prepareRequest($this->message, $this->additionalData);
        try {
            $response = $this->performRequest($requestData);
            $solarSystemResult = $this->processResponse($response);
        } catch (GuzzleException $e) {
            $solarSystemResult = $this->processException($e);
        }

        $solarSystemResult->setRequestData($requestData);

        return $solarSystemResult;
    }

    /*
     * prepareRequest method should reformat input data
     * according to specific Solar System requirements
     *
     * @param Message $message
     * @param array   $additionalData
     * @return array
     */
    abstract protected function prepareRequest(Message $message, array $additionalData): array;

    /*
     * performRequest method handles request execution
     * accepts prepared request data
     * and returns raw request result
     *
     * @param array $requestData
     * @return GuzzleResponse
     */
    abstract protected function performRequest(array $requestData): GuzzleResponse;

    /*
     * precessResponse breaks response down to universal SolarSystemResponseContract
     *
     * @param GuzzleResponse $response
     * @return SolarSystemResponseContract
     */
    abstract protected function processResponse(GuzzleResponse $response): SolarSystemResponseContract;

    /*
     * precessException understands exceptions and convert it to universal SolarSystemResponseContract
     *
     * @param \Exception $e
     * @return SolarSystemResponseContract
     */
    abstract protected function processException(\Exception $e): SolarSystemResponseContract;

    /**
     * fillBasicResponse method handles successful response and
     * pre-initializes common fields for SolarSystemResponseContract
     *
     * @param GuzzleResponse $response
     * @return SolarSystemResponseContract
     */
    protected function fillBasicResponse(GuzzleResponse $response): SolarSystemResponseContract
    {
        $solarSystemResponse = new SolarSystemResponse;

        $solarSystemResponse->setRequestName($this->getRequestName());
        $solarSystemResponse->setResponseStatusCode($response->getStatusCode());
        $solarSystemResponse->setResponseContent($response->getBody()->getContents());

        return $solarSystemResponse;
    }

    /*
     * fillBasicException method handles response that trows exception
     * pre-initializes common fields for SolarSystemResponseContract
     *
     * @param \Exception $exception
     * @return SolarSystemResponseContract
     */
    protected function fillBasicException(\Exception $exception): SolarSystemResponseContract
    {
        $solarSystemResponse = new SolarSystemResponse();

        $solarSystemResponse->setRequestName($this->getRequestName());
        $solarSystemResponse->setResponseStatusCode($exception->getCode());
        $solarSystemResponse->setResponseContent($this->getMessage($exception));
        $solarSystemResponse->setExceptionMessage($exception->getMessage());
        $solarSystemResponse->makeServerError($this->config('server_error_expires_at'));

        return $solarSystemResponse;
    }

    /**
     * @param \Exception $e
     * @return string
     */
    protected function getMessage(\Exception $e): ?string
    {
        if (is_object($e->getResponse())) {
            return $e->getResponse()->getBody(true)->getContents();
        } else {
            return $e->getResponse();
        }
    }

    /**
     * @param string $attr
     * @param mixed  $default
     * @return mixed
     */
    protected function config(string $attr, $default = null)
    {
        return Config::get("{$this->configKey}.$attr", $default);
    }

    /**
     * @return string
     */
    protected function getRequestName(): string
    {
        return static::REQUEST_NAME;
    }
}
