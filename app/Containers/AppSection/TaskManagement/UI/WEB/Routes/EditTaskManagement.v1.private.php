<?php

use App\Containers\AppSection\TaskManagement\UI\WEB\Controllers\EditTaskManagementController;
use Illuminate\Support\Facades\Route;

Route::get('tasks/{id}/edit', EditTaskManagementController::class)->middleware(['auth:web']);
