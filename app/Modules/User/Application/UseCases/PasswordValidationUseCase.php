<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

readonly class PasswordValidationUseCase
{
    public function __construct(private readonly UserRepositoryInterface $userRepository){}

    public function execute(string $password): bool|array
    {
        return $this->userRepository->passwordValidation($password);
    }
}
