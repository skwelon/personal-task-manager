<?php

namespace App\Containers\AppSection\TaskManagement\Actions;

use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Containers\AppSection\TaskManagement\Tasks\CreateTaskManagementTask;
use App\Containers\AppSection\TaskManagement\UI\API\Requests\CreateTaskManagementRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateTaskManagementAction extends ParentAction
{
    public function __construct(private readonly CreateTaskManagementTask $createTaskManagementTask) {}

    public function run(CreateTaskManagementRequest $request): TaskManagement
    {
        $data = $request->sanitize(['title', 'description', 'is_completed']);
        $data['user_id'] = $request->user()->id;

        return $this->createTaskManagementTask->run($data);
    }
}
