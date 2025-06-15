<?php

use App\Http\Controllers\AffiliateController;
use Illuminate\Support\Facades\Route;

Route::get('/affiliates', [AffiliateController::class, 'index'])->name('affiliates.index');
