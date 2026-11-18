<?php

namespace App\Containers\AppSection\Authentication\UI\WEB\Controllers;

use App\Containers\AppSection\Authentication\Actions\Api\WebClient\RefreshTokenAction;
use App\Containers\AppSection\Authentication\Actions\Web\LogoutAction;
use App\Containers\AppSection\Authentication\Data\Factories\TokenAttributeFormatter;
use App\Containers\AppSection\Authentication\Values\RefreshToken;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

final class RefreshSessionTokenController extends WebController
{
    public function __invoke(
        Request $request,
        RefreshTokenAction $refreshTokenAction,
        TokenAttributeFormatter $tokenFormatter,
        LogoutAction $logoutAction,
    ): JsonResponse {
        $session = $request->session();
        $refreshToken = $session->get('api_refresh_token');

        if (!is_string($refreshToken) || $refreshToken === '') {
            $logoutAction->run($session);

            return response()->json(['message' => 'Your session expired. Please log in again.'], 401);
        }

        try {
            $token = $refreshTokenAction->run(RefreshToken::create($refreshToken));
            $tokenAttributes = $tokenFormatter->format($token->accessToken);

            $session->put([
                'api_access_token' => $token->accessToken,
                'api_access_token_id' => $tokenAttributes['oauth_access_token_id'],
                'api_refresh_token' => $token->refreshToken->value(),
                'api_token_expires_at' => now()->addSeconds($token->expiresIn)->timestamp,
            ]);

            return response()->json([
                'access_token' => $token->accessToken,
                'expires_in' => $token->expiresIn,
            ]);
        } catch (Throwable $exception) {
            report($exception);
            $logoutAction->run($session);

            return response()->json(['message' => 'Your session expired. Please log in again.'], 401);
        }
    }
}
