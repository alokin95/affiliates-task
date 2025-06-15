<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAffiliateController;

Route::get('/affiliates', [ApiAffiliateController::class, 'index'])
    ->name('api.affiliates.index');
