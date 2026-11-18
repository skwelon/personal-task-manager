<?php

namespace App\Containers\AppSection\TaskManagement\Tests\Unit\Tasks;

use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Containers\AppSection\TaskManagement\Tasks\ListTaskManagementsTask;
use App\Containers\AppSection\TaskManagement\Tests\UnitTestCase;
use App\Containers\AppSection\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ListTaskManagementsTask::class)]
final class ListTaskManagementsTaskTest extends UnitTestCase
{
    public function testListTaskManagements(): void
    {
        $user = User::factory()->createOne();
        $otherUser = User::factory()->createOne();
        $ownTasks = TaskManagement::factory()->count(3)->create(['user_id' => $user->id]);
        TaskManagement::factory()->create(['user_id' => $otherUser->id]);

        $result = app(ListTaskManagementsTask::class)->run($user->id);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(3, $result);
        $this->assertSame([$user->id], $result->getCollection()->pluck('user_id')->unique()->all());
        $this->assertEqualsCanonicalizing(
            $ownTasks->modelKeys(),
            $result->getCollection()->modelKeys(),
        );
    }

    public function testListTaskManagementsFiltersByCompletionStatus(): void
    {
        $user = User::factory()->createOne();
        $otherUser = User::factory()->createOne();
        $incompleteTask = TaskManagement::factory()->create(['user_id' => $user->id, 'is_completed' => false]);
        $completedTask = TaskManagement::factory()->create(['user_id' => $user->id, 'is_completed' => true]);
        TaskManagement::factory()->create(['user_id' => $otherUser->id, 'is_completed' => false]);
        TaskManagement::factory()->create(['user_id' => $otherUser->id, 'is_completed' => true]);

        $incomplete = app(ListTaskManagementsTask::class)->run($user->id, false);
        $completed = app(ListTaskManagementsTask::class)->run($user->id, true);

        $this->assertEquals([$incompleteTask->getKey()], $incomplete->getCollection()->modelKeys());
        $this->assertEquals([$completedTask->getKey()], $completed->getCollection()->modelKeys());
        $this->assertSame([$user->id], $incomplete->getCollection()->pluck('user_id')->unique()->all());
        $this->assertSame([$user->id], $completed->getCollection()->pluck('user_id')->unique()->all());
    }
}
