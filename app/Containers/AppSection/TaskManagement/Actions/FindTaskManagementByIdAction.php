<?php

namespace App\Containers\AppSection\TaskManagement\Actions;

use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Containers\AppSection\TaskManagement\Tasks\FindTaskManagementByIdTask;
use App\Containers\AppSection\TaskManagement\UI\API\Requests\FindTaskManagementByIdRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindTaskManagementByIdAction extends ParentAction
{
    public function __construct(private readonly FindTaskManagementByIdTask $findTaskManagementByIdTask) {}

    public function run(FindTaskManagementByIdRequest $request): TaskManagement
    {
        return $this->findTaskManagementByIdTask->run($request->id, $request->user()->id);
    }
}
