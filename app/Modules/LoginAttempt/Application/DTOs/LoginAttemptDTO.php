<?php

namespace App\Modules\LoginAttempt\Application\DTOs;

class LoginAttemptDTO
{
    public string $userName;
    public ?int $userId;
    public bool $successful;
    public string $ipAddress;
    public string $userAgent;
    public ?string $failureReason;
    public ?int $failedAttemptsCount;
    public ?int $companyId;
    public ?int $roleId;
    public string $attemptAt;

    public function __construct(array $data)
    {
        $this->userName = $data['userName'];
        $this->userId = $data['userId'] ?? null;
        $this->successful = $data['successful'];
        $this->ipAddress = $data['ipAddress'];
        $this->userAgent = $data['userAgent'];
        $this->failureReason = $data['failureReason'] ?? null;
        $this->failedAttemptsCount = $data['failedAttemptsCount'] ?? null;
        $this->companyId = $data['companyId'] ?? null;
        $this->roleId = $data['roleId'] ?? null;
        $this->attemptAt = $data['attemptAt'];
    }
}
