<?php

namespace App\Containers\AppSection\TaskManagement\Actions;

use App\Containers\AppSection\TaskManagement\Tasks\DeleteTaskManagementTask;
use App\Containers\AppSection\TaskManagement\UI\API\Requests\DeleteTaskManagementRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeleteTaskManagementAction extends ParentAction
{
    public function __construct(private readonly DeleteTaskManagementTask $deleteTaskManagementTask) {}

    public function run(DeleteTaskManagementRequest $request): bool
    {
        return $this->deleteTaskManagementTask->run($request->id, $request->user()->id);
    }
}
