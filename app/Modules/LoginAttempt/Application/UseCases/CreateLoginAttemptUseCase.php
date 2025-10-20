<?php

namespace App\Modules\LoginAttempt\Application\UseCases;

use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\LoginAttempt\Application\DTOs\LoginAttemptDTO;
use App\Modules\LoginAttempt\Domain\Entities\LoginAttempt;
use App\Modules\LoginAttempt\Domain\Interfaces\LoginAttemptRepositoryInterface;

readonly class CreateLoginAttemptUseCase
{
    public function __construct(
        private readonly LoginAttemptRepositoryInterface $loginAttemptRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
    ){}

    public function execute(LoginAttemptDTO $loginAttemptDTO): void
    {
        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepository);
        $company = $companyUseCase->execute($loginAttemptDTO->companyId);

        $loginAttempt = new LoginAttempt(
            id: 0,
            userName: $loginAttemptDTO->userName,
            userId: $loginAttemptDTO->userId,
            successful: $loginAttemptDTO->successful,
            ipAddress: $loginAttemptDTO->ipAddress,
            userAgent: $loginAttemptDTO->userAgent,
            failureReason: $loginAttemptDTO->failureReason,
            failedAttemptsCount: $loginAttemptDTO->failedAttemptsCount,
            company: $company,
            roleId: $loginAttemptDTO->roleId,
            roleName: null,
            attemptAt: $loginAttemptDTO->attemptAt
        );

        $this->loginAttemptRepository->save($loginAttempt);
    }
}
