<?php

namespace App\Containers\AppSection\TaskManagement\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class FindTaskManagementByIdRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
