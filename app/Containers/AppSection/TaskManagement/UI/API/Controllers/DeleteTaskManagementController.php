<?php

namespace App\Containers\AppSection\TaskManagement\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\TaskManagement\Actions\DeleteTaskManagementAction;
use App\Containers\AppSection\TaskManagement\UI\API\Requests\DeleteTaskManagementRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class DeleteTaskManagementController extends ApiController
{
    public function __invoke(DeleteTaskManagementRequest $request, DeleteTaskManagementAction $action): JsonResponse
    {
        $action->run($request);

        return Response::noContent();
    }
}
