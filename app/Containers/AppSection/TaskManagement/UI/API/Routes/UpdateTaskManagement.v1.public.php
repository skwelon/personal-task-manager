<?php

/**
 * @apiGroup           TaskManagement
 * @apiName            UpdateTask
 *
 * @api                {PATCH} /v1/tasks/:id Update a task
 * @apiDescription     Applies any supplied supported fields to a task owned by the authenticated user. An empty request leaves the task unchanged.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated user (Bearer access token required)
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} Authorization Bearer {access_token}
 *
 * @apiParam           {String} id Hashed task identifier returned by this API.
 * @apiBody            {String} [title] Task title, 1–150 characters when provided.
 * @apiBody            {String} [description] Task description; may be null.
 * @apiBody            {Boolean} [is_completed] Completion status.
 *
 * @apiParamExample    {json} Request:
 * {"title":"Review final project plan","is_completed":true}
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {"data":{"type":"TaskManagement","id":"hashed-task-id","user_id":12,"title":"Review final project plan","description":"Check milestones","is_completed":true,"created_at":"2026-11-18T10:00:00.000000Z","updated_at":"2026-11-18T10:05:00.000000Z","readable_created_at":"5 minutes ago","readable_updated_at":"1 second ago"},"meta":{"include":[]}}
 *
 * @apiError 401 Bearer token is missing, invalid, or expired.
 * @apiError 404 Task does not exist or is owned by another user.
 * @apiError 422 Submitted field values failed validation.
 * @apiError 400 Request body is not valid JSON.
 * @apiError 429 API request rate limit exceeded.
 */

use App\Containers\AppSection\TaskManagement\UI\API\Controllers\UpdateTaskManagementController;
use Illuminate\Support\Facades\Route;

Route::patch('tasks/{id}', UpdateTaskManagementController::class)
    ->middleware(['auth:api']);

