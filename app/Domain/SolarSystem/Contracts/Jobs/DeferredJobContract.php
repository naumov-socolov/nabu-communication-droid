<?php

namespace App\Domain\SolarSystem\Contracts\Jobs;

use App\Domain\SolarSystem\Services\MessageUpdater;

interface DeferredJobContract
{
    const FAST_APPROVAL = 'fast_approval';
    const DEFERRED_APPROVAL = 'deferred_approval';
    const DEFERRED_ISSUE = 'deferred_issue';

    /**
     * @param array          $requestHandlers
     * @param MessageUpdater $messageUpdater
     * @param string         $configKey
     */
    public function __construct(
        array $requestHandlers,
        MessageUpdater $messageUpdater,
        string $configKey
    );

    public function handle();
}
