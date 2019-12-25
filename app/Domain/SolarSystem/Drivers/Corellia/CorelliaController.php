<?php

namespace App\Domain\SolarSystem\Drivers\Corellia;

use App\Domain\SolarSystem\Contracts\DriverControllerContract;
use App\Domain\SolarSystem\Contracts\Jobs\DeferredJobContract;
use App\Domain\SolarSystem\Drivers\BaseController;
use App\Domain\SolarSystem\Drivers\Corellia\Jobs\CorelliaApprovedDeferredJob;
use App\Domain\SolarSystem\Drivers\Corellia\Jobs\CorelliaDeferredIssuedJob;
use App\Domain\SolarSystem\Drivers\Corellia\RequestHandlers\CorelliaDeferredApprovalRequestHandler;
use App\Domain\SolarSystem\Drivers\Corellia\RequestHandlers\CorelliaDeferredIssuedRequestHandler;
use App\Domain\SolarSystem\Drivers\Corellia\RequestHandlers\CorelliaFastApprovalRequestHandler;
use App\Domain\SolarSystem\Events\SolarSystemRequestReceived;
use App\Domain\SolarSystem\Services\RequestStrategies\DeferredApprovalStrategyBase;

class CorelliaController extends BaseController implements DriverControllerContract
{
    public function handle()
    {
        $message = resolve(
            DeferredApprovalStrategyBase::class,
            [
                'messageUpdater' => $this->messageUpdaterService,
            ]
        )->assignRequestHandlers(
            [
                DeferredApprovalStrategyBase::FAST_APPROVAL =>
                    $this->initRequestHandler(CorelliaFastApprovalRequestHandler::class),
            ]
        )->assignDeferredJobs(
            [
                DeferredApprovalStrategyBase::DEFERRED_APPROVAL =>
                    $this->getDeferredApprovalJob(),
                DeferredApprovalStrategyBase::DEFERRED_ISSUE =>
                    $this->getDeferredIssueJob(),
            ]
        )->handle();

        event(new SolarSystemRequestReceived($message));
    }

    /**
     * @return DeferredJobContract
     */
    protected function getDeferredApprovalJob(): DeferredJobContract
    {
        return $this->initDeferredJob(
            CorelliaApprovedDeferredJob::class,
            [
                DeferredApprovalStrategyBase::DEFERRED_APPROVAL =>
                    $this->composeRequestHandler(CorelliaDeferredApprovalRequestHandler::class),
            ]
        );
    }

    /**
     * @return DeferredJobContract
     */
    protected function getDeferredIssueJob(): DeferredJobContract
    {
        return $this->initDeferredJob(
            CorelliaDeferredIssuedJob::class,
            [
                DeferredApprovalStrategyBase::DEFERRED_ISSUE =>
                    $this->composeRequestHandler(CorelliaDeferredIssuedRequestHandler::class),
            ]
        );
    }
}
