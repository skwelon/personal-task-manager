<?php

use App\Containers\AppSection\Authentication\UI\WEB\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('register', [RegisterController::class, 'showForm'])
    ->name('register')
    ->middleware(['guest']);

Route::post('register', RegisterController::class)
    ->name('register.store')
    ->middleware(['guest']);
