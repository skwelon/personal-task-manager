<?php

namespace App\Containers\AppSection\TaskManagement\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\TaskManagement\Actions\ListTaskManagementsAction;
use App\Containers\AppSection\TaskManagement\UI\API\Requests\ListTaskManagementsRequest;
use App\Containers\AppSection\TaskManagement\UI\API\Transformers\TaskManagementTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListTaskManagementsController extends ApiController
{
    public function __invoke(ListTaskManagementsRequest $request, ListTaskManagementsAction $action): JsonResponse
    {
        $taskManagement = $action->run($request);

        return Response::create($taskManagement, TaskManagementTransformer::class)->ok();
    }
}
