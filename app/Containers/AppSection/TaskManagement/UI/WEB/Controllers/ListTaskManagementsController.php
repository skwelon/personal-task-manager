<?php

namespace App\Containers\AppSection\TaskManagement\UI\WEB\Controllers;

use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class ListTaskManagementsController extends WebController
{
    public function __invoke(): View
    {
        return view('appSection@taskManagement::index');
    }
}
