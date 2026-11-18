<?php

namespace App\Containers\AppSection\TaskManagement\Tests\Unit\Tasks;

use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Containers\AppSection\TaskManagement\Tasks\FindTaskManagementByIdTask;
use App\Containers\AppSection\TaskManagement\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FindTaskManagementByIdTask::class)]
final class FindTaskManagementByIdTaskTest extends UnitTestCase
{
    public function testFindTaskManagementById(): void
    {
        $task = TaskManagement::factory()->createOne();
        $found = app(FindTaskManagementByIdTask::class)->run($task->id, $task->user_id);

        $this->assertSame($task->id, $found->id);
    }
}
