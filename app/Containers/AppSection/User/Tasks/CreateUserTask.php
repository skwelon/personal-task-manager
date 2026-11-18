<?php

namespace App\Containers\AppSection\User\Tasks;

use App\Containers\AppSection\User\Data\Repositories\UserRepository;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Validation\ValidationException;

final class CreateUserTask extends ParentTask
{
    public function __construct(
        private readonly UserRepository $repository,
    ) {
    }

    public function run(array $data): User
    {
        tap(validator(
            [
                'email' => strtolower($data['email']),
            ],
            [
                'email' => ['unique:users,email'],
            ],
        ))->validate();

        try {
            return $this->repository->create($data);
        } catch (UniqueConstraintViolationException $exception) {
            $message = strtolower($exception->getMessage());
            $isEmailConstraint = str_contains($message, 'users_email_unique')
                || str_contains($message, 'users.email');

            if (!$isEmailConstraint) {
                throw $exception;
            }

            throw ValidationException::withMessages([
                'email' => __('validation.unique', ['attribute' => 'email']),
            ]);
        }
    }
}
