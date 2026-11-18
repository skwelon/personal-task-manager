<?php

namespace App\Containers\AppSection\TaskManagement\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateTaskManagementRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'is_completed' => ['sometimes', 'boolean'],
        ];
    }
}
