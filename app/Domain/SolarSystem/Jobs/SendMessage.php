<?php

namespace App\Domain\SolarSystem\Jobs;

use App\Domain\SolarSystem\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;

class SendMessage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    const DEFAULT_WAITING_EXPIRES = 60 * 3;
    const CONFIG_NAME = 'solar_systems';
    const DRIVER_CLASS = 'driver_class';
    const WAITING_EXPIRES_KEY = 'waiting_expires_at';

    public $tries = 3;

    protected $message;

    protected $additionalData;

    /**
     * @param Message $message
     * @param array   $additionalData
     */
    public function __construct(Message $message, $additionalData = [])
    {
        $this->message = $message;
        $this->additionalData = $additionalData;
    }

    public function handle()
    {
        $this->setMessageToWaiting();

        resolve(
            $this->getDriverControllerClass(),
            [
                'message' => $this->message,
                'additionalData' => $this->additionalData,
            ]
        )->handle();
    }

    protected function setMessageToWaiting()
    {
        $this->message->status = Message::WAITING;
        $this->message->expired_at = now()->addSeconds($this->expiredAt());

        $this->message->save();
    }

    /**
     * @return string
     */
    private function getDriverControllerClass(): string
    {
        return Config::get(
            self::CONFIG_NAME . ".{$this->message->solarSystem->driver_name}." . self::DRIVER_CLASS
        );
    }

    /**
     * @return int
     */
    private function expiredAt(): int
    {
        return Config::get(
            self::CONFIG_NAME . ".{$this->message->solarSystem->driver_name}." . self::WAITING_EXPIRES_KEY,
            self::DEFAULT_WAITING_EXPIRES
        );
    }
}
