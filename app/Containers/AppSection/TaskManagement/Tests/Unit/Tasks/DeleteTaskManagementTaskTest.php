<?php

namespace App\Containers\AppSection\TaskManagement\Tests\Unit\Tasks;

use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Containers\AppSection\TaskManagement\Tasks\DeleteTaskManagementTask;
use App\Containers\AppSection\TaskManagement\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(DeleteTaskManagementTask::class)]
final class DeleteTaskManagementTaskTest extends UnitTestCase
{
    public function testDeleteTaskManagement(): void
    {
        $task = TaskManagement::factory()->createOne();
        $result = app(DeleteTaskManagementTask::class)->run($task->id, $task->user_id);

        $this->assertTrue($result);
        $this->assertModelMissing($task);
    }
}
