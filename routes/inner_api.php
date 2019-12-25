<?php

use App\Http\Controllers\SendMessageController;
use App\Http\Controllers\SolarSystemController;

Route::fallback(function() {
    return response()->json(['error' => 'Check your turbo-lasers, pal, you might need some'], 404);
})->name('fallback');

//Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['auth']], function() {
Route::group(['prefix' => 'user', 'as' => 'user.', ], function() {
    Route::get('solar-system/{solarSystem}/send-message', SendMessageController::class)
        ->name('solar_system.send_message');

    Route::get('solar-system/index', [SolarSystemController::class, 'index'])
        ->name('solar_system.index');
});
