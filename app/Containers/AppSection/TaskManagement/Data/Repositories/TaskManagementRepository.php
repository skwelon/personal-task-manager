<?php

namespace App\Containers\AppSection\TaskManagement\Data\Repositories;

use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of TaskManagement
 *
 * @extends ParentRepository<TModel>
 */
final class TaskManagementRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'title' => 'like',
        'is_completed' => '=',
    ];
}
