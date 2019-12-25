<?php

namespace App\Domain\SolarSystem\Services;

use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Contracts\LoggerContract;
use App\Domain\SolarSystem\Models\RequestLog;

class DbLogger implements LoggerContract
{
    public $messageId;

    private $log;

    /**
     * @param int $messageId
     */
    public function __construct(int $messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * @param SolarSystemResponseContract $result
     */
    public function log(SolarSystemResponseContract $result)
    {
        $this->log = RequestLog::make();
        $this->fill($result);
        $this->log->save();
    }

    /**
     * @param SolarSystemResponseContract $result
     * @return RequestLog
     */
    protected function fill(SolarSystemResponseContract $result): RequestLog
    {
        return $this->log->fill(
            [
                'message_id' => $this->messageId,
                'request_name' => $result->requestName,
                'request_data' => json_encode($result->requestData),
                'response_data' => json_encode(
                    [
                        'status_code' => $result->statusCode,
                        'body' => $result->responseContent,
                        'exception_message' => $result->exceptionMessage,
                    ]
                ),
                'status' => $result->status,
            ]
        );
    }
}
