<?php

/**
 * @apiGroup           TaskManagement
 * @apiName            ListTasks
 *
 * @api                {GET} /v1/tasks List the authenticated user's tasks
 * @apiDescription     Returns only tasks owned by the authenticated user. Use is_completed to filter by completion status.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated user (Bearer access token required)
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} Authorization Bearer {access_token}
 *
 * @apiParam           {Boolean} [is_completed] Filter tasks by completion status.
 * @apiParam           {Integer} [page] Pagination page number.
 *
 * @apiParamExample    {url} Incomplete tasks:
 * /v1/tasks?is_completed=0
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {"data":[{"type":"TaskManagement","id":"hashed-task-id","user_id":12,"title":"Review project plan","description":"Check milestones","is_completed":false,"created_at":"2026-11-18T10:00:00.000000Z","updated_at":"2026-11-18T10:00:00.000000Z","readable_created_at":"1 second ago","readable_updated_at":"1 second ago"}],"meta":{"include":[],"pagination":{"total":1,"count":1,"per_page":10,"current_page":1,"total_pages":1,"links":{}}}}
 *
 * @apiError 401 Bearer token is missing, invalid, or expired.
 * @apiError 422 is_completed must be a boolean value.
 * @apiError 429 API request rate limit exceeded.
 */

use App\Containers\AppSection\TaskManagement\UI\API\Controllers\ListTaskManagementsController;
use Illuminate\Support\Facades\Route;

Route::get('tasks', ListTaskManagementsController::class)
    ->middleware(['auth:api']);

