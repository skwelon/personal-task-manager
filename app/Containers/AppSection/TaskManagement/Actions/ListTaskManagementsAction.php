<?php

namespace App\Containers\AppSection\TaskManagement\Actions;

use App\Containers\AppSection\TaskManagement\Tasks\ListTaskManagementsTask;
use App\Containers\AppSection\TaskManagement\UI\API\Requests\ListTaskManagementsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListTaskManagementsAction extends ParentAction
{
    public function __construct(private readonly ListTaskManagementsTask $listTaskManagementsTask) {}

    public function run(ListTaskManagementsRequest $request): mixed
    {
        $isCompleted = $request->has('is_completed')
            ? filter_var($request->input('is_completed'), FILTER_VALIDATE_BOOLEAN)
            : null;

        return $this->listTaskManagementsTask->run($request->user()->id, $isCompleted);
    }
}
