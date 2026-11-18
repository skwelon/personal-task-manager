<?php

namespace App\Containers\AppSection\TaskManagement\Tasks;

use App\Containers\AppSection\TaskManagement\Data\Repositories\TaskManagementRepository;
use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateTaskManagementTask extends ParentTask
{
    public function __construct(private readonly TaskManagementRepository $repository) {}

    public function run(array $data): TaskManagement
    {
        return $this->repository->create($data);
    }
}
