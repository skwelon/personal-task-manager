<?php

use App\Containers\AppSection\TaskManagement\UI\WEB\Controllers\CreateTaskManagementController;
use Illuminate\Support\Facades\Route;

Route::get('tasks/create', CreateTaskManagementController::class)->middleware(['auth:web']);
