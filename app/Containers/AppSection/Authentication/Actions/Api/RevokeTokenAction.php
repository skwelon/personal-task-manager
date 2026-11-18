<?php

namespace App\Containers\AppSection\Authentication\Actions\Api;

use App\Containers\AppSection\Authentication\Values\RefreshToken;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Actions\Action as ParentAction;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Cookie as CookieFacade;
use Symfony\Component\HttpFoundation\Cookie;

final class RevokeTokenAction extends ParentAction
{
    public function run(User|null $user, string|null $accessTokenId = null): Cookie
    {
        if ($user !== null) {
            $accessTokenId ??= $user->token()?->oauth_access_token_id;

            if ($accessTokenId !== null) {
                $token = Passport::token()->newQuery()
                    ->whereKey($accessTokenId)
                    ->where('user_id', $user->getAuthIdentifier())
                    ->first();

                if ($token !== null) {
                    $token->refreshToken?->revoke();
                    $token->revoke();
                }
            }
        }

        return CookieFacade::forget(RefreshToken::cookieName());
    }
}
