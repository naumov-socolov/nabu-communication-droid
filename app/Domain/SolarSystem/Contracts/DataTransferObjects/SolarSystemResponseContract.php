<?php

namespace App\Domain\SolarSystem\Contracts\DataTransferObjects;

interface SolarSystemResponseContract
{
    public function makeIssued(int $timeout);

    public function makeApproved(int $timeout);

    public function makeCancelled(int $timeout);

    public function makePending(int $timeout);

    public function makeRejected(int $timeout);

    public function makeServerError(int $timeout);

    public function makeDataError(int $timeout);

    public function setRequestName($requestName);

    public function setRequestData($request);

    public function setResponseContent($response);

    public function setExceptionMessage($message);

    public function setResponseStatusCode($statusCode);

    public function setHashKey($partnerRequestId);

    public function setProceedLink($link);

    public function setExpiredAt(int $timeout);

    /*
     * Checks if result status is successful: is approved, in pending or issued
     */
    public function isSuccessful();
}
