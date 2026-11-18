<?php

namespace App\Containers\AppSection\TaskManagement\UI\API\Transformers;

use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class TaskManagementTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];
    protected array $availableIncludes = [];

    public function transform(TaskManagement $taskmanagement): array
    {
        return [
            'type' => $taskmanagement->getResourceKey(),
            'id' => $taskmanagement->getHashedKey(),
            'user_id' => $taskmanagement->user_id,
            'title' => $taskmanagement->title,
            'description' => $taskmanagement->description,
            'is_completed' => $taskmanagement->is_completed,
            'created_at' => $taskmanagement->created_at,
            'updated_at' => $taskmanagement->updated_at,
            'readable_created_at' => $taskmanagement->created_at?->diffForHumans(),
            'readable_updated_at' => $taskmanagement->updated_at?->diffForHumans(),
        ];
    }
}
