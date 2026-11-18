<?php

namespace App\Containers\AppSection\TaskManagement\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\TaskManagement\Actions\CreateTaskManagementAction;
use App\Containers\AppSection\TaskManagement\UI\API\Requests\CreateTaskManagementRequest;
use App\Containers\AppSection\TaskManagement\UI\API\Transformers\TaskManagementTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class CreateTaskManagementController extends ApiController
{
    public function __invoke(CreateTaskManagementRequest $request, CreateTaskManagementAction $action): JsonResponse
    {
        $taskManagement = $action->run($request);

        return Response::create($taskManagement, TaskManagementTransformer::class)->created();
    }
}
