<?php

namespace App\Modules\LoginAttempt\Application\UseCases;

use App\Modules\LoginAttempt\Application\DTOs\LoginAttemptDTO;
use App\Modules\LoginAttempt\Domain\Entities\LoginAttempt;
use App\Modules\LoginAttempt\Domain\Interfaces\LoginAttemptRepositoryInterface;

readonly class CreateLoginAttemptUseCase
{
    public function __construct(private readonly LoginAttemptRepositoryInterface $loginAttemptRepository){}

    public function execute(LoginAttemptDTO $loginAttemptDTO): void
    {
        $loginAttempt = new LoginAttempt(
            id: 0,
            userName: $loginAttemptDTO->userName,
            userId: $loginAttemptDTO->userId,
            successful: $loginAttemptDTO->successful,
            ipAddress: $loginAttemptDTO->ipAddress,
            userAgent: $loginAttemptDTO->userAgent,
            failureReason: $loginAttemptDTO->failureReason,
            failedAttemptsCount: $loginAttemptDTO->failedAttemptsCount,
            companyId: $loginAttemptDTO->companyId,
            roleId: $loginAttemptDTO->roleId,
            attemptAt: $loginAttemptDTO->attemptAt
        );

        $this->loginAttemptRepository->save($loginAttempt);
    }
}
