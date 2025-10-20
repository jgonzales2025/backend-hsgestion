<?php

namespace App\Modules\LoginAttempt\Infrastructure\Persistence;

use App\Modules\LoginAttempt\Domain\Entities\LoginAttempt;
use App\Modules\LoginAttempt\Domain\Interfaces\LoginAttemptRepositoryInterface;
use App\Modules\LoginAttempt\Infrastructure\Models\EloquentLoginAttempt;

class EloquentLoginAttemptRepository implements LoginAttemptRepositoryInterface
{
    public function findAllLoginAttempts(): array
    {
        $loginAttempts = EloquentLoginAttempt::all()->sortByDesc('created_at');

        return $loginAttempts->map(function ($loginAttempt) {
            return new LoginAttempt(
                id: $loginAttempt->id,
                userName: $loginAttempt->username,
                userId: $loginAttempt->user_id,
                successful: $loginAttempt->successful,
                ipAddress: $loginAttempt->ip_address,
                userAgent: $loginAttempt->user_agent,
                failureReason: $loginAttempt->failure_reason,
                failedAttemptsCount: $loginAttempt->failed_attempts_count,
                companyId: $loginAttempt->company_id,
                roleId: $loginAttempt->role_id,
                attemptAt: $loginAttempt->attempted_at,
            );
        })->toArray();
    }

    public function save(LoginAttempt $loginAttempt): void
    {
        EloquentLoginAttempt::create([
            'username' => $loginAttempt->getUserName(),
            'user_id' => $loginAttempt->getUserId(),
            'successful' => $loginAttempt->getSuccessful(),
            'ip_address' => $loginAttempt->getIpAddress(),
            'user_agent' => $loginAttempt->getUserAgent(),
            'failure_reason' => $loginAttempt->getFailureReason(),
            'failed_attempts_count' => $loginAttempt->getFailedAttemptsCount(),
            'company_id' => $loginAttempt->getCompanyId(),
            'role_id' => $loginAttempt->getRoleId(),
            'attempted_at' => $loginAttempt->getAttemptAt()
        ]);
    }
}
