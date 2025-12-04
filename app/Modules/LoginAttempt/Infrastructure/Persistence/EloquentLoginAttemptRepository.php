<?php

namespace App\Modules\LoginAttempt\Infrastructure\Persistence;

use App\Models\Role;
use App\Modules\LoginAttempt\Domain\Entities\LoginAttempt;
use App\Modules\LoginAttempt\Domain\Interfaces\LoginAttemptRepositoryInterface;
use App\Modules\LoginAttempt\Infrastructure\Models\EloquentLoginAttempt;

class EloquentLoginAttemptRepository implements LoginAttemptRepositoryInterface
{
    public function findAllLoginAttempts(?string $description, ?string $roleId)
    {
        $loginAttempts = EloquentLoginAttempt::with('company')->orderBy('created_at', 'desc')
        ->when($description, fn($query) => $query->where('username', 'like', "%{$description}%")
            ->orWhere('ip_address', 'like', "%{$description}%")
            ->orWhere('user_agent', 'like', "%{$description}%")
            ->orWhere('failure_reason', 'like', "%{$description}%"))
        ->when($roleId, fn($query) => $query->where('role_id', $roleId))
        ->paginate(10);

        $loginAttempts->getCollection()->transform(function ($loginAttempt) {
            $roleName = Role::find($loginAttempt->role_id);
            return new LoginAttempt(
                id: $loginAttempt->id,
                userName: $loginAttempt->username,
                userId: $loginAttempt->user_id,
                successful: $loginAttempt->successful,
                ipAddress: $loginAttempt->ip_address,
                userAgent: $loginAttempt->user_agent,
                failureReason: $loginAttempt->failure_reason,
                failedAttemptsCount: $loginAttempt->failed_attempts_count,
                company: $loginAttempt->company?->toDomain($loginAttempt->company),
                roleId: $loginAttempt->role_id,
                roleName: $roleName?->name,
                attemptAt: $loginAttempt->created_at,
            );
        });

        return $loginAttempts;
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
            'company_id' => $loginAttempt->getCompany()?->getId(),
            'role_id' => $loginAttempt->getRoleId()
        ]);
    }
}
