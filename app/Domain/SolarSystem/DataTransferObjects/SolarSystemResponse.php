<?php

namespace App\Domain\SolarSystem\DataTransferObjects;

use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Models\Message;

class SolarSystemResponse implements SolarSystemResponseContract
{
    const APPROVED = Message::APPROVED;
    const PENDING = Message::PENDING;
    const CANCELLED = Message::CANCELLED;
    const DATA_ERROR = Message::DATA_ERROR;
    const SERVER_ERROR = Message::SERVER_ERROR;
    const ISSUED = Message::ISSUED;
    const REJECTED = Message::REJECTED;

    public $status;

    public $requestName;

    public $requestData;

    public $statusCode;

    public $responseContent;

    public $exceptionMessage;

    public $hashKey;

    public $expiredAt;

    public $proceedLink;

    public function isApproved()
    {
        return $this->status == self::APPROVED;
    }

    public function isCancelled()
    {
        return $this->status == self::CANCELLED;
    }

    public function isRejected()
    {
        return $this->status == self::REJECTED;
    }

    public function isIssued()
    {
        return $this->status == self::ISSUED;
    }

    public function isPending()
    {
        return $this->status == self::PENDING;
    }

    public function isSuccessful()
    {
        return $this->isApproved() || $this->isPending();
    }

    public function makeIssued(int $timeout)
    {
        $this->status = self::ISSUED;
        $this->setExpiredAt($timeout);
    }

    public function makeRejected(int $timeout)
    {
        $this->status = self::REJECTED;
        $this->setExpiredAt($timeout);
    }

    public function makeApproved(int $timeout)
    {
        $this->status = self::APPROVED;
        $this->setExpiredAt($timeout);
    }

    public function makePending(int $timeout)
    {
        $this->status = self::PENDING;
        $this->setExpiredAt($timeout);
    }

    public function makeCancelled(int $timeout)
    {
        $this->status = self::CANCELLED;
        $this->setExpiredAt($timeout);
    }

    public function makeDataError(int $timeout)
    {
        $this->status = self::DATA_ERROR;
        $this->setExpiredAt($timeout);
    }

    public function makeServerError(int $timeout)
    {
        $this->status = self::SERVER_ERROR;
        $this->setExpiredAt($timeout);
    }

    public function setRequestData($requestData)
    {
        $this->requestData = $requestData;
    }

    public function setProceedLink($proceedLink)
    {
        $this->proceedLink = $proceedLink;
    }

    public function setRequestName($requestName)
    {
        $this->requestName = $requestName;
    }

    public function setResponseStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function setResponseContent($response)
    {
        $this->responseContent = $response;
    }

    public function setExceptionMessage($message)
    {
        $this->exceptionMessage = $message;
    }

    public function setHashKey($hashKey)
    {
        $this->hashKey = $hashKey;
    }

    public function setExpiredAt(int $timeout)
    {
        $this->expiredAt = now()->addSeconds($timeout);
    }
}
