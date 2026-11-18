<?php

/**
 * @apiGroup           TaskManagement
 * @apiName            CreateTask
 *
 * @api                {POST} /v1/tasks Create a task
 * @apiDescription     Creates a task for the authenticated user. The owner is taken from the Bearer token; client-supplied user_id is ignored.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated user (Bearer access token required)
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} Authorization Bearer {access_token}
 *
 * @apiBody            {String} title Required. Task title, 1–150 characters.
 * @apiBody            {String} [description] Optional task description.
 * @apiBody            {Boolean} [is_completed=false] Optional initial completion state.
 *
 * @apiParamExample    {json} Request:
 * {"title":"Review project plan","description":"Check milestones","is_completed":false}
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 201 Created
 * {"data":{"type":"TaskManagement","id":"hashed-task-id","user_id":12,"title":"Review project plan","description":"Check milestones","is_completed":false,"created_at":"2026-11-18T10:00:00.000000Z","updated_at":"2026-11-18T10:00:00.000000Z","readable_created_at":"1 second ago","readable_updated_at":"1 second ago"},"meta":{"include":[]}}
 *
 * @apiError 401 Bearer token is missing, invalid, or expired.
 * @apiError 422 Validation failed (for example, missing title or title longer than 150 characters).
 * @apiError 400 Request body is not valid JSON.
 * @apiError 429 API request rate limit exceeded.
 */

use App\Containers\AppSection\TaskManagement\UI\API\Controllers\CreateTaskManagementController;
use Illuminate\Support\Facades\Route;

Route::post('tasks', CreateTaskManagementController::class)
    ->middleware(['auth:api']);

