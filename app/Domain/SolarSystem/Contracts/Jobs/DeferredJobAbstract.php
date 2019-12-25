<?php

namespace App\Domain\SolarSystem\Contracts\Jobs;

use App\Domain\SolarSystem\Services\MessageUpdater;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

abstract class DeferredJobAbstract implements ShouldQueue, DeferredJobContract
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $requestHandlers;

    protected $messageUpdater;

    protected $configKey;

    /**
     * @param array          $requestHandlers
     * @param MessageUpdater $messageUpdater
     * @param string         $configKey
     */
    public function __construct(
        array $requestHandlers,
        MessageUpdater $messageUpdater,
        string $configKey
    ) {
        $this->requestHandlers = $requestHandlers;
        $this->messageUpdater = $messageUpdater;
        $this->configKey = $configKey;
    }

    abstract public function handle();

    /**
     * @return bool
     */
    protected function isMaxAttemptsExceeded(): bool
    {
        return $this->attempts() >= $this->tries;
    }

    /**
     * @param string $attr
     * @param null   $default
     * @return mixed
     */
    protected function config(string $attr, $default = null)
    {
        return Config::get($this->configKey . "." . $attr, $default);
    }

    /**
     * @return bool
     */
    protected function approvedWithHashKey(): bool
    {
        return isset($this->messageUpdater->message->refresh()->hash_key);
    }
}
