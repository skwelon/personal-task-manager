<?php

namespace App\Containers\AppSection\Authentication\Actions\Web;

use App\Containers\AppSection\Authentication\Actions\Api\WebClient\IssueTokenAction;
use App\Containers\AppSection\Authentication\Data\Factories\TokenAttributeFormatter;
use App\Containers\AppSection\Authentication\Values\UserCredential;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Throwable;

final class LoginAction extends ParentAction
{
    public function __construct(
        private readonly IssueTokenAction $issueTokenAction,
        private readonly TokenAttributeFormatter $tokenFormatter,
    ) {}

    public function run(
        string $email,
        string $password,
        bool $remember,
    ): RedirectResponse {
        $credentials = [
            'email' => static fn(Builder $query): Builder => $query
                ->orWhereRaw('lower(email) = lower(?)', [$email]),
            'password' => $password,
        ];

        if (! Auth::guard('web')->attempt($credentials, $remember)) {
            return back()
                ->withErrors([
                    'email' => __('auth.failed'),
                ])
                ->onlyInput('email');
        }

        session()->regenerate();

        try {
            /** @var User $user */
            $user = Auth::guard('web')->user();

            $token = $this->issueTokenAction->run(
                UserCredential::create($user->email, $password),
                $user,
            );
            $tokenAttributes = $this->tokenFormatter->format($token->accessToken);

            session()->put([
                'api_access_token' => $token->accessToken,
                'api_access_token_id' => $tokenAttributes['oauth_access_token_id'],
                'api_refresh_token' => $token->refreshToken->value(),
                'api_token_expires_at' => now()
                    ->addSeconds($token->expiresIn)
                    ->timestamp,
            ]);
        } catch (Throwable $exception) {
            report($exception);

            Auth::guard('web')->logout();

            session()->forget([
                'api_access_token',
                'api_access_token_id',
                'api_refresh_token',
                'api_token_expires_at',
            ]);

            session()->invalidate();
            session()->regenerateToken();

            return back()->withErrors([
                'email' => 'We could not create an API token. Please try signing in again.',
            ]);
        }

        return redirect()->intended('/tasks');
    }
}
