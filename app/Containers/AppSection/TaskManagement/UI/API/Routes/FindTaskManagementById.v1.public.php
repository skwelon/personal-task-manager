<?php

/**
 * @apiGroup           TaskManagement
 * @apiName            FindTask
 *
 * @api                {GET} /v1/tasks/:id Get a task
 * @apiDescription     Returns one task if it belongs to the authenticated user.
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
 * HTTP/1.1 200 OK
 * {"data":{"type":"TaskManagement","id":"hashed-task-id","user_id":12,"title":"Review project plan","description":"Check milestones","is_completed":false,"created_at":"2026-11-18T10:00:00.000000Z","updated_at":"2026-11-18T10:00:00.000000Z","readable_created_at":"1 second ago","readable_updated_at":"1 second ago"},"meta":{"include":[]}}
 *
 * @apiError 401 Bearer token is missing, invalid, or expired.
 * @apiError 404 Task does not exist or is owned by another user.
 * @apiError 429 API request rate limit exceeded.
 */

use App\Containers\AppSection\TaskManagement\UI\API\Controllers\FindTaskManagementByIdController;
use Illuminate\Support\Facades\Route;

Route::get('tasks/{id}', FindTaskManagementByIdController::class)
    ->middleware(['auth:api']);

