<?php

namespace App\Containers\AppSection\TaskManagement\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateTaskManagementRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:150'],
            'description' => ['sometimes', 'nullable', 'string'],
            'is_completed' => ['sometimes', 'boolean'],
        ];
    }
}
