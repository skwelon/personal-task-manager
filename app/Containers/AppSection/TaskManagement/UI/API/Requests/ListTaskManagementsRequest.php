<?php

namespace App\Containers\AppSection\TaskManagement\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListTaskManagementsRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'is_completed' => ['sometimes', 'boolean'],
        ];
    }
}
