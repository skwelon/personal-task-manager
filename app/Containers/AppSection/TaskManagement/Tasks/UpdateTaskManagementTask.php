<?php

namespace App\Containers\AppSection\TaskManagement\Tasks;

use App\Containers\AppSection\TaskManagement\Data\Repositories\TaskManagementRepository;
use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class UpdateTaskManagementTask extends ParentTask
{
    public function __construct(private readonly TaskManagementRepository $repository) {}

    public function run(array $data, $id, int $userId): TaskManagement
    {
        $task = $this->repository->scope(fn ($query) => $query->where('user_id', $userId))->findOrFail($id);
        $task->fill($data);
        $this->repository->save($task);

        return $task;
    }
}
