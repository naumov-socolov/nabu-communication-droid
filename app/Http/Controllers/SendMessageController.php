<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domain\SolarSystem\Models\SolarSystem;
use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Domain\SolarSystem\Rules\SolarSystemConfig as SolarSystemConfigRule;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Domain\SolarSystem\Services\DispatchSendMessage;
use App\Domain\SolarSystem\Resources\Message as MessageResource;
use Illuminate\Http\JsonResponse;

class SendMessageController
{
    /**
     * @param Request     $request
     * @param SolarSystem $solarSystem
     * @return JsonResponse
     */
    public function __invoke(Request $request, SolarSystem $solarSystem)
    {
        // skipping login form for demo purpose
        auth()->login(User::all()->first());

        $this->evaluateSolarSystemAndAmount($request, $solarSystem);

        return
            (new MessageResource(
                resolve(DispatchSendMessage::class)
                    ->handle(
                        $request,
                        $solarSystem
                    )
            ))
                ->response()
                ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * @param Request     $request
     * @param SolarSystem $solarSystem
     */
    protected function evaluateSolarSystemAndAmount(Request $request, SolarSystem $solarSystem)
    {
        $validator = Validator::make(
            array_merge($request->all(), ['solarSystem' => $solarSystem]),
            [
                'solarSystem' => resolve(SolarSystemConfigRule::class),
                'amount' => 'required|integer|min:25|max:55000',
            ]
        );

        if ($validator->fails()) {
            throw new HttpResponseException(
                response()
                    ->json(
                        ['errors' => $validator->errors()],
                        JsonResponse::HTTP_UNPROCESSABLE_ENTITY
                    )
            );
        }
    }
}
