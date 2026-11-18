<?php

namespace App\Containers\AppSection\TaskManagement\Tests\Functional\API;

use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Containers\AppSection\TaskManagement\Tests\Functional\ApiTestCase;
use App\Containers\AppSection\TaskManagement\UI\API\Controllers\ListTaskManagementsController;
use App\Containers\AppSection\User\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
final class ListTaskManagementsTest extends ApiTestCase
{
    public function testListTaskManagementsOnlyForAuthenticatedUser(): void
    {
        $user = User::factory()->createOne();
        $this->actingAs($user);
        $ownTasks = TaskManagement::factory()->count(2)->create(['user_id' => $user->id]);
        TaskManagement::factory()->create(['is_completed' => false]);

        $response = $this->getJson(action(ListTaskManagementsController::class));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 2)
            ->where('data.0.user_id', $user->id)
            ->where('data.1.user_id', $user->id)
            ->etc());

        $this->assertEqualsCanonicalizing(
            $ownTasks->map(fn (TaskManagement $task) => $task->getHashedKey())->all(),
            collect($response->json('data'))->pluck('id')->all(),
        );
    }

    public function testAdminStillOnlyReceivesTheirOwnTasks(): void
    {
        $admin = User::factory()->superAdmin()->createOne();
        $this->actingAs($admin, 'api');
        $ownTask = TaskManagement::factory()->create(['user_id' => $admin->id]);
        TaskManagement::factory()->create();

        $response = $this->getJson(action(ListTaskManagementsController::class));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 1)
            ->where('data.0.user_id', $admin->id)
            ->where('data.0.id', $ownTask->getHashedKey())
            ->etc());
    }

    public function testListTaskManagementsFiltersIncompleteTasksWithoutLeakingOtherUsersTasks(): void
    {
        $user = User::factory()->createOne();
        $this->actingAs($user);
        $incompleteTask = TaskManagement::factory()->create(['user_id' => $user->id, 'is_completed' => false]);
        TaskManagement::factory()->create(['user_id' => $user->id, 'is_completed' => true]);
        TaskManagement::factory()->create(['is_completed' => false]);

        $response = $this->getJson(action(ListTaskManagementsController::class).'?is_completed=0');

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 1)
            ->where('data.0.user_id', $user->id)
            ->where('data.0.id', $incompleteTask->getHashedKey())
            ->where('data.0.is_completed', false)
            ->etc());
    }

    public function testListTaskManagementsFiltersCompletedTasks(): void
    {
        $user = User::factory()->createOne();
        $this->actingAs($user);
        TaskManagement::factory()->create(['user_id' => $user->id, 'is_completed' => false]);
        $completedTask = TaskManagement::factory()->create(['user_id' => $user->id, 'is_completed' => true]);
        TaskManagement::factory()->create(['is_completed' => true]);

        $response = $this->getJson(action(ListTaskManagementsController::class).'?is_completed=1');

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 1)
            ->where('data.0.user_id', $user->id)
            ->where('data.0.id', $completedTask->getHashedKey())
            ->where('data.0.is_completed', true)
            ->etc());
    }
}
