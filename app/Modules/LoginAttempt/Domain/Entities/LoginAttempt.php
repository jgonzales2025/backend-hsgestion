<?php

namespace App\Modules\LoginAttempt\Domain\Entities;

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
    private ?int $companyId;
    private ?int $roleId;
    private string $attemptAt;

    public function __construct(int $id, string $userName, ?int $userId, bool $successful, string $ipAddress, string $userAgent, ?string $failureReason, ?int $failedAttemptsCount, ?int $companyId, ?int $roleId, string $attemptAt)
    {
        $this->id = $id;
        $this->userName = $userName;
        $this->userId = $userId;
        $this->successful = $successful;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->failureReason = $failureReason;
        $this->failedAttemptsCount = $failedAttemptsCount;
        $this->companyId = $companyId;
        $this->roleId = $roleId;
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
    public function getCompanyId(): ?int { return $this->companyId; }
    public function getRoleId(): ?int { return $this->roleId; }
    public function getAttemptAt(): string { return $this->attemptAt; }
}
