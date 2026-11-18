<?php

namespace App\Containers\AppSection\TaskManagement\Data\Seeders;

use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Seeders\Seeder as ParentSeeder;

final class TaskManagementSeeder_1 extends ParentSeeder
{
    public function run(): void
    {
        $userOne = User::query()->firstOrCreate(
            ['email' => 'taskuser1@example.com'],
            [
                'name' => 'Task Manager User 1',
                'password' => 'password',
            ],
        );

        $userTwo = User::query()->firstOrCreate(
            ['email' => 'taskuser2@example.com'],
            [
                'name' => 'Task Manager User 2',
                'password' => 'password',
            ],
        );

        $this->seedTasks($userOne, [
            ['title' => 'Prepare project documentation', 'description' => 'Write the project setup and usage guide.', 'is_completed' => false],
            ['title' => 'Review API endpoints', 'description' => 'Check the task API endpoints and responses.', 'is_completed' => true],
            ['title' => 'Test task ownership', 'description' => 'Confirm users can access only their own tasks.', 'is_completed' => false],
        ]);

        $this->seedTasks($userTwo, [
            ['title' => 'Plan weekly priorities', 'description' => 'Choose the most important tasks for this week.', 'is_completed' => true],
            ['title' => 'Review personal task list', 'description' => 'Update and organize the outstanding tasks.', 'is_completed' => false],
        ]);
    }

    private function seedTasks(User $user, array $tasks): void
    {
        foreach ($tasks as $task) {
            $user->tasks()->firstOrCreate(
                ['title' => $task['title']],
                [
                    'description' => $task['description'],
                    'is_completed' => $task['is_completed'],
                ],
            );
        }
    }
}
