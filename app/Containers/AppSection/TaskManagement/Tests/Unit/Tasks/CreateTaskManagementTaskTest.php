<?php

namespace App\Containers\AppSection\TaskManagement\Tests\Unit\Tasks;

use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Containers\AppSection\TaskManagement\Tasks\CreateTaskManagementTask;
use App\Containers\AppSection\TaskManagement\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(CreateTaskManagementTask::class)]
final class CreateTaskManagementTaskTest extends UnitTestCase
{
    public function testCreateTaskManagement(): void
    {
        $task = app(CreateTaskManagementTask::class)->run([
            'user_id' => 1,
            'title' => 'Test task',
            'description' => null,
            'is_completed' => false,
        ]);

        $this->assertModelExists($task);
        $this->assertSame('Test task', $task->title);
    }
}
