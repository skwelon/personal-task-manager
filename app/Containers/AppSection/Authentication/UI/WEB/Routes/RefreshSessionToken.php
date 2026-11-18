<?php

use App\Containers\AppSection\Authentication\UI\WEB\Controllers\RefreshSessionTokenController;
use Illuminate\Support\Facades\Route;

Route::post('session/refresh', RefreshSessionTokenController::class)
    ->name('session.refresh')
    ->middleware(['auth:web']);
