<?php

namespace App\Containers\AppSection\Authentication\UI\WEB\Controllers;

use App\Containers\AppSection\Authentication\Actions\RegisterUserAction;
use App\Containers\AppSection\Authentication\UI\WEB\Requests\RegisterUserRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class RegisterController extends WebController
{
    public function __invoke(RegisterUserRequest $request, RegisterUserAction $action): RedirectResponse
    {
        $action->transactionalRun($request->sanitize([
            'name',
            'email',
            'password',
        ]));

        return redirect()->route('login.form');
    }

    public function showForm(): View
    {
        return view('appSection@authentication::register');
    }
}
