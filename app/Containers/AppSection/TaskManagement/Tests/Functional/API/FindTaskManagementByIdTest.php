<?php

namespace App\Containers\AppSection\TaskManagement\Tests\Functional\API;

use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Containers\AppSection\TaskManagement\Tests\Functional\ApiTestCase;
use App\Containers\AppSection\TaskManagement\UI\API\Controllers\FindTaskManagementByIdController;
use App\Containers\AppSection\User\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
final class FindTaskManagementByIdTest extends ApiTestCase
{
    public function testFindTaskManagement(): void
    {
        $user = User::factory()->createOne();
        $this->actingAs($user);
        $task = TaskManagement::factory()->createOne(['user_id' => $user->id]);

        $response = $this->getJson(action(FindTaskManagementByIdController::class, ['id' => $task->getHashedKey()]));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json->has('data')
            ->where('data.id', $task->getHashedKey())
            ->etc());
    }
}
