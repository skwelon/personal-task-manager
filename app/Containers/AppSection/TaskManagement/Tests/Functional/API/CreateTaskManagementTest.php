<?php

namespace App\Containers\AppSection\TaskManagement\Tests\Functional\API;

use App\Containers\AppSection\TaskManagement\Tests\Functional\ApiTestCase;
use App\Containers\AppSection\TaskManagement\UI\API\Controllers\CreateTaskManagementController;
use App\Containers\AppSection\User\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
final class CreateTaskManagementTest extends ApiTestCase
{
    public function testCreateTaskManagement(): void
    {
        $this->actingAs($user = User::factory()->createOne());
        $data = ['title' => 'Test task', 'description' => 'Description', 'is_completed' => false];

        $response = $this->postJson(action(CreateTaskManagementController::class), $data);

        $response->assertCreated();
        $response->assertJson(fn (AssertableJson $json) => $json->has('data')
            ->where('data.type', 'TaskManagement')
            ->where('data.title', 'Test task')
            ->where('data.user_id', $user->id)
            ->etc());
    }
}
