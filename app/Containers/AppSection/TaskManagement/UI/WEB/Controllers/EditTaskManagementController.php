<?php

namespace App\Containers\AppSection\TaskManagement\UI\WEB\Controllers;

use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class EditTaskManagementController extends WebController
{
    public function __invoke(string $id): View
    {
        return view('appSection@taskManagement::edit', ['taskId' => $id]);
    }
}
