<?php

namespace App\Containers\AppSection\TaskManagement\Tasks;

use App\Containers\AppSection\TaskManagement\Data\Repositories\TaskManagementRepository;
use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindTaskManagementByIdTask extends ParentTask
{
    public function __construct(private readonly TaskManagementRepository $repository) {}

    public function run($id, int $userId): TaskManagement
    {
        return $this->repository->scope(fn ($query) => $query->where('user_id', $userId))->findOrFail($id);
    }
}
