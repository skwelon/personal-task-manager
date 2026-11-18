<?php

namespace App\Containers\AppSection\TaskManagement\Actions;

use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Containers\AppSection\TaskManagement\Tasks\UpdateTaskManagementTask;
use App\Containers\AppSection\TaskManagement\UI\API\Requests\UpdateTaskManagementRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateTaskManagementAction extends ParentAction
{
    public function __construct(private readonly UpdateTaskManagementTask $updateTaskManagementTask) {}

    public function run(UpdateTaskManagementRequest $request): TaskManagement
    {
        $data = $request->sanitize(['title', 'description', 'is_completed']);

        return $this->updateTaskManagementTask->run($data, $request->id, $request->user()->id);
    }
}
