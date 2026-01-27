<?php

use App\Http\Controllers\Api\IntegrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FakeExternalController;

Route::middleware('throttle:10,1')->group(function () {
    Route::post('/integrations/customers', [IntegrationController::class, 'store']);
});

Route::get('/integrations/customers', [IntegrationController::class, 'index']);
Route::get('/integrations/customers/{id}', [IntegrationController::class, 'show']);

Route::post('/fake/external/customers', [FakeExternalController::class, 'store']);