<?php

namespace App\Domain\SolarSystem\Contracts\Services;

use App\Domain\SolarSystem\Contracts\MessageUpdaterContract;
use App\Domain\SolarSystem\Models\Message;

interface RequestStrategyContract
{
    const QUEUE_NAME = "solar_system";
    const HASH_KEY_ATTR = 'hash_key';

    const APPROPRIATE_TYPES = [self::FAST_APPROVAL, self::DEFERRED_APPROVAL, self::DEFERRED_ISSUE];
    const FAST_APPROVAL = 'fast_approval';
    const DEFERRED_APPROVAL = 'deferred_approval';
    const DEFERRED_ISSUE = 'deferred_issue';

    /**
     * @param MessageUpdaterContract $messageUpdater
     */
    public function __construct(MessageUpdaterContract $messageUpdater);

    /**
     * handle method manages all the sequence of Solar System request routine.
     * It knows what type of communication is accepted
     * and which drivers are responsible for each step.
     * So it can handle single driver call,
     * or dispatch sequence of request drivers if necessary.
     *
     *  @return Message
     */
    public function handle(): Message;
}
