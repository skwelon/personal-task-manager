<?php

namespace App\Containers\AppSection\TaskManagement\Tests\Functional\API;

use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Containers\AppSection\TaskManagement\Tests\Functional\ApiTestCase;
use App\Containers\AppSection\TaskManagement\UI\API\Controllers\UpdateTaskManagementController;
use App\Containers\AppSection\User\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
final class UpdateTaskManagementTest extends ApiTestCase
{
    public function testUpdateExistingTaskManagement(): void
    {
        $user = User::factory()->createOne();
        $this->actingAs($user);
        $task = TaskManagement::factory()->createOne(['user_id' => $user->id]);
        $data = ['title' => 'Updated title', 'is_completed' => true];

        $response = $this->patchJson(action(UpdateTaskManagementController::class, ['id' => $task->getHashedKey()]), $data);

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json->has('data')
            ->where('data.type', 'TaskManagement')
            ->where('data.id', $task->getHashedKey())
            ->where('data.title', 'Updated title')
            ->where('data.is_completed', true)
            ->etc());
    }
}
