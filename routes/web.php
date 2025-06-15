<?php

use App\Http\Controllers\AffiliateController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AffiliateController::class, 'index'])->name('affiliates.index');
