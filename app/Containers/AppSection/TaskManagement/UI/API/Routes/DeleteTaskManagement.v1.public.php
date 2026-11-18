<?php

/**
 * @apiGroup           TaskManagement
 * @apiName            DeleteTask
 *
 * @api                {DELETE} /v1/tasks/:id Delete a task
 * @apiDescription     Permanently removes a task owned by the authenticated user.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated user (Bearer access token required)
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} Authorization Bearer {access_token}
 *
 * @apiParam           {String} id Hashed task identifier returned by this API.
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 204 No Content
 * (Empty response body.)
 *
 * @apiError 401 Bearer token is missing, invalid, or expired.
 * @apiError 404 Task does not exist or is owned by another user.
 * @apiError 429 API request rate limit exceeded.
 */

use App\Containers\AppSection\TaskManagement\UI\API\Controllers\DeleteTaskManagementController;
use Illuminate\Support\Facades\Route;

Route::delete('tasks/{id}', DeleteTaskManagementController::class)
    ->middleware(['auth:api']);

