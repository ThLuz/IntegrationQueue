<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\IntegrationWebController;

Route::get('/', function () {
    return redirect('/integrations');
});

Route::get('/integrations', [IntegrationWebController::class, 'index']);
