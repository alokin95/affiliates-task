<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAffiliateController;

Route::middleware('api')
    ->prefix('api')
    ->group(function () {
        Route::get('/affiliates', [ApiAffiliateController::class, 'index'])
            ->name('api.affiliates.index');
    });
