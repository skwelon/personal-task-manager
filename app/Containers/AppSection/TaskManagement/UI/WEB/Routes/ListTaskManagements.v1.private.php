<?php

use App\Containers\AppSection\TaskManagement\UI\WEB\Controllers\ListTaskManagementsController;
use Illuminate\Support\Facades\Route;

Route::get('tasks', ListTaskManagementsController::class)
    ->name('tasks')
    ->middleware(['auth:web']);
