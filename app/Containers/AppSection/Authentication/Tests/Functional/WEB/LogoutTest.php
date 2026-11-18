<?php

namespace App\Containers\AppSection\Authentication\Tests\Functional\WEB;

use App\Containers\AppSection\Authentication\Data\Factories\ClientFactory;
use App\Containers\AppSection\Authentication\Data\Factories\TokenAttributeFormatter;
use App\Containers\AppSection\Authentication\Tests\Functional\WebTestCase;
use App\Containers\AppSection\Authentication\UI\WEB\Controllers\LoginController;
use App\Containers\AppSection\Authentication\UI\WEB\Controllers\HomePageController;
use App\Containers\AppSection\Authentication\UI\WEB\Controllers\LogoutController;
use App\Containers\AppSection\TaskManagement\UI\API\Controllers\ListTaskManagementsController;
use App\Containers\AppSection\User\Models\User;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(LogoutController::class)]
final class LogoutTest extends WebTestCase
{
    public function testLogout(): void
    {
        $this->actingAs(User::factory()->createOne(), 'web');
        $this->assertAuthenticated('web');

        $response = $this->post(action(LogoutController::class));

        $response->assertRedirect('/login');
        $this->assertGuest('web');
    }

    public function testWebLogoutRevokesItsPassportAccessAndRefreshTokens(): void
    {
        ClientFactory::webClient();
        $user = User::factory()->createOne([
            'email' => 'logout@example.com',
            'password' => 'test-password',
        ]);

        $this->post(action(LoginController::class), [
            'email' => $user->email,
            'password' => 'test-password',
        ]);

        $session = app('session.store');
        $accessToken = $session->get('api_access_token');
        $accessTokenId = $session->get('api_access_token_id');

        $this->assertTrue(is_string($accessToken) && $accessToken !== '');
        $this->assertTrue(is_string($accessTokenId) && $accessTokenId !== '');
        $tokenAttributes = app(TokenAttributeFormatter::class)->format($accessToken);
        $this->assertTrue($tokenAttributes['oauth_access_token_id'] === $accessTokenId);

        $passportToken = Passport::token()->newQuery()->findOrFail($accessTokenId);
        $refreshToken = $passportToken->refreshToken;
        $this->assertNotNull($refreshToken);

        $this->withToken($accessToken)
            ->getJson(action(ListTaskManagementsController::class))
            ->assertOk();

        $logoutResponse = $this->post(action(LogoutController::class));

        $logoutResponse->assertRedirect('/login');
        $this->assertGuest('web');
        $this->assertTrue($passportToken->fresh()->revoked);
        $this->assertTrue($refreshToken->fresh()->revoked);
        $this->assertFalse($session->has('api_access_token'));
        $this->assertFalse($session->has('api_access_token_id'));
        $this->assertFalse($session->has('api_refresh_token'));

        $this->app['auth']->forgetGuards();
        $this->withToken($accessToken)
            ->getJson(action(ListTaskManagementsController::class))
            ->assertUnauthorized();
    }

    public function testLogoutWhileUnauthenticatedIsRejected(): void
    {
        $response = $this->post(action(LogoutController::class));

        $response->assertRedirect(action(HomePageController::class));
    }
}
