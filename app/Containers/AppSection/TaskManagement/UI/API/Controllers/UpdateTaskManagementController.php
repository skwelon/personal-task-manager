<?php

namespace App\Containers\AppSection\TaskManagement\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\TaskManagement\Actions\UpdateTaskManagementAction;
use App\Containers\AppSection\TaskManagement\UI\API\Requests\UpdateTaskManagementRequest;
use App\Containers\AppSection\TaskManagement\UI\API\Transformers\TaskManagementTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class UpdateTaskManagementController extends ApiController
{
    public function __invoke(UpdateTaskManagementRequest $request, UpdateTaskManagementAction $action): JsonResponse
    {
        $taskManagement = $action->run($request);

        return Response::create($taskManagement, TaskManagementTransformer::class)->ok();
    }
}
