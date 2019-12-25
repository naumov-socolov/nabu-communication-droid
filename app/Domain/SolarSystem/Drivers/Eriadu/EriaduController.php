<?php

namespace App\Domain\SolarSystem\Drivers\Eriadu;

use App\Domain\SolarSystem\Contracts\DriverControllerContract;
use App\Domain\SolarSystem\Contracts\Services\RequestStrategyContract;
use App\Domain\SolarSystem\Drivers\BaseController;
use App\Domain\SolarSystem\Drivers\Eriadu\RequestHandlers\EriaduFastApprovalRequestHandler;
use App\Domain\SolarSystem\Events\SolarSystemRequestReceived;
use App\Domain\SolarSystem\Services\RequestStrategies\FastApprovalStrategy;

class EriaduController extends BaseController implements DriverControllerContract
{
    public function handle()
    {
        $message = resolve(
            FastApprovalStrategy::class,
            [
                'messageUpdater' => $this->messageUpdaterService,
            ]
        )->assignRequestHandlers(
            [
                RequestStrategyContract::FAST_APPROVAL =>
                    $this->initRequestHandler(EriaduFastApprovalRequestHandler::class),
            ]
        )->handle();

        event(new SolarSystemRequestReceived($message));
    }
}
