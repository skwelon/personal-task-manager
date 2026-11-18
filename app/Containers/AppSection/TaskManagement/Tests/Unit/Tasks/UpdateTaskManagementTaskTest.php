<?php

namespace App\Containers\AppSection\TaskManagement\Tests\Unit\Tasks;

use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Containers\AppSection\TaskManagement\Tasks\UpdateTaskManagementTask;
use App\Containers\AppSection\TaskManagement\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(UpdateTaskManagementTask::class)]
final class UpdateTaskManagementTaskTest extends UnitTestCase
{
    public function testUpdateTaskManagement(): void
    {
        $task = TaskManagement::factory()->createOne();
        $updated = app(UpdateTaskManagementTask::class)->run(
            ['title' => 'Updated title'],
            $task->id,
            $task->user_id,
        );

        $this->assertSame($task->id, $updated->id);
        $this->assertSame('Updated title', $updated->title);
    }
}
