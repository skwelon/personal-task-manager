<?php

namespace App\Containers\AppSection\Authentication\Actions\Web;

use App\Containers\AppSection\Authentication\Actions\Api\RevokeTokenAction;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

final class LogoutAction extends ParentAction
{
    public function __construct(
        private readonly RevokeTokenAction $revokeTokenAction,
    ) {}

    public function run(Session $session): RedirectResponse
    {
        $user = Auth::guard('web')->user();
        $accessTokenId = $session->get('api_access_token_id');

        if ($user !== null) {
            $this->revokeTokenAction->run($user, is_string($accessTokenId) ? $accessTokenId : null);
        }

        Auth::guard('web')->logout();

        $session->forget([
            'api_access_token',
            'api_access_token_id',
            'api_refresh_token',
            'api_token_expires_at',
        ]);

        $session->invalidate();
        $session->regenerateToken();

        return redirect('/login');
    }
}
