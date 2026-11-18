<?php

namespace App\Containers\AppSection\TaskManagement\Tasks;

use App\Containers\AppSection\TaskManagement\Data\Repositories\TaskManagementRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class ListTaskManagementsTask extends ParentTask
{
    public function __construct(private readonly TaskManagementRepository $repository) {}

    public function run(int $userId, ?bool $isCompleted = null): mixed
    {
        return $this->repository
            ->scope(function (Builder|Model $query) use ($userId, $isCompleted) {
                if ($query instanceof Model) {
                    $query = $query->newQuery();
                }

                $query->where('user_id', $userId);
                if ($isCompleted !== null) {
                    $query->where('is_completed', $isCompleted);
                }
                return $query;
            })
            ->addRequestCriteria()
            ->paginate();
    }
}
