<?php

namespace App\Domain\SolarSystem\Services;

use App\Domain\SolarSystem\Contracts\MessageUpdaterContract;
use App\Domain\SolarSystem\Contracts\DataTransferObjects\SolarSystemResponseContract;
use App\Domain\SolarSystem\Models\Message;

class MessageUpdater implements MessageUpdaterContract
{
    public $message;

    /**
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * @param SolarSystemResponseContract $response
     * @return Message
     */
    public function update(SolarSystemResponseContract $response): Message
    {
        $this->message->status = $response->status;
        $this->message->hash_key = $response->hashKey;
        $this->message->expired_at = $response->expiredAt;
        if ($response->proceedLink) {
            $this->message->proceed_link = $response->proceedLink;
        }

        if (!empty($response->proceedLink)) {
            resolve(CreateUrl::class)->handle(
                $response->proceedLink,
                $this->message->solarSystem
            );
        }

        $this->message->save();

        return $this->message;
    }
}
