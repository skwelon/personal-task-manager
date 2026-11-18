<?php

namespace App\Containers\AppSection\TaskManagement\Tests\Unit\Factories;

use App\Containers\AppSection\TaskManagement\Data\Factories\TaskManagementFactory;
use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Containers\AppSection\TaskManagement\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TaskManagementFactory::class)]
final class TaskManagementFactoryTest extends UnitTestCase
{
    public function testCreateTaskManagement(): void
    {
        $taskManagement = TaskManagement::factory()->make();

        $this->assertInstanceOf(TaskManagement::class, $taskManagement);
    }
}
