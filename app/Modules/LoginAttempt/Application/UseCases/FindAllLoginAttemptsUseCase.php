<?php

namespace App\Modules\LoginAttempt\Application\UseCases;

use App\Modules\LoginAttempt\Domain\Interfaces\LoginAttemptRepositoryInterface;

readonly class FindAllLoginAttemptsUseCase
{
    public function __construct(private readonly LoginAttemptRepositoryInterface $loginAttemptRepository){}

    public function execute(): array
    {
        return $this->loginAttemptRepository->findAllLoginAttempts();
    }
}
