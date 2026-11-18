<?php

namespace App\Containers\AppSection\TaskManagement\Tests\Unit\Data\Migrations;

use App\Containers\AppSection\TaskManagement\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
final class TaskManagementsMigrationTest extends UnitTestCase
{
    public function testTasksTableHasExpectedColumns(): void
    {
        $columns = [
            'id' => 'int8',
            'user_id' => 'int8',
            'title' => 'varchar',
            'description' => 'text',
            'is_completed' => 'bool',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
        ];

        $this->assertDatabaseTable('tasks', $columns);
    }
}
