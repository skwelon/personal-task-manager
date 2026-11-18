<?php

namespace App\Containers\AppSection\TaskManagement\Data\Factories;

use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Factories\Factory as ParentFactory;

/**
 * @template TModel of TaskManagement
 *
 * @extends ParentFactory<TModel>
 */
final class TaskManagementFactory extends ParentFactory
{
    /** @var class-string<TModel> */
    protected $model = TaskManagement::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'is_completed' => false,
        ];
    }
}
