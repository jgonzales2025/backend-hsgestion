<?php

namespace App\Modules\LoginAttempt\Domain\Entities;

use App\Modules\Company\Domain\Entities\Company;
use App\Modules\Role\Domain\Entities\Role;

class LoginAttempt
{
    private int $id;
    private string $userName;
    private ?int $userId;
    private bool $successful;
    private string $ipAddress;
    private string $userAgent;
    private ?string $failureReason;
    private ?int $failedAttemptsCount;
    private ?Company $company;
    private ?int $roleId;
    private ?string $roleName;
    private string $attemptAt;

    public function __construct(int $id, string $userName, ?int $userId, bool $successful, string $ipAddress, string $userAgent, ?string $failureReason, ?int $failedAttemptsCount, ?Company $company, ?int $roleId, ?string $roleName, string $attemptAt)
    {
        $this->id = $id;
        $this->userName = $userName;
        $this->userId = $userId;
        $this->successful = $successful;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->failureReason = $failureReason;
        $this->failedAttemptsCount = $failedAttemptsCount;
        $this->company = $company;
        $this->roleId = $roleId;
        $this->roleName = $roleName;
        $this->attemptAt = $attemptAt;
    }

    public function getId(): int { return $this->id; }
    public function getUserName(): string { return $this->userName; }
    public function getUserId(): ?int { return $this->userId; }
    public function getSuccessful(): bool { return $this->successful; }
    public function getIpAddress(): string { return $this->ipAddress; }
    public function getUserAgent(): string { return $this->userAgent; }
    public function getFailureReason(): ?string { return $this->failureReason; }
    public function getFailedAttemptsCount(): ?int { return $this->failedAttemptsCount; }
    public function getCompany(): ?Company { return $this->company; }
    public function getRoleId(): ?int { return $this->roleId; }
    public function getRoleName(): ?string { return $this->roleName; }
    public function getAttemptAt(): string { return $this->attemptAt; }
}
