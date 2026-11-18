<?php

namespace App\Containers\AppSection\TaskManagement\Tests\Functional\API;

use App\Containers\AppSection\TaskManagement\Models\TaskManagement;
use App\Containers\AppSection\TaskManagement\Tests\Functional\ApiTestCase;
use App\Containers\AppSection\TaskManagement\UI\API\Controllers\DeleteTaskManagementController;
use App\Containers\AppSection\User\Models\User;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
final class DeleteTaskManagementTest extends ApiTestCase
{
    public function testDeleteExistingTaskManagement(): void
    {
        $user = User::factory()->createOne();
        $this->actingAs($user);
        $task = TaskManagement::factory()->createOne(['user_id' => $user->id]);

        $response = $this->deleteJson(action(DeleteTaskManagementController::class, ['id' => $task->getHashedKey()]));

        $response->assertNoContent();
        $this->assertModelMissing($task);
    }
}
