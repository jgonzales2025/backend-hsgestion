<?php

namespace App\Modules\LoginAttempt\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginAttemptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'username' => $this->resource->getUsername(),
            'user_id' => $this->resource->getUserId(),
            'successful' => $this->resource->getSuccessful(),
            'ip_address' => $this->resource->getIpAddress(),
            'user_agent' => $this->resource->getUserAgent(),
            'failure_reason' => $this->resource->getFailureReason(),
            'failed_attempts_count' => $this->resource->getFailedAttemptsCount(),
            'company_id' => $this->resource->getCompanyId(),
            'role_id' => $this->resource->getRoleId(),
            'attempted_at' => $this->resource->getAttemptAt(),
        ];
    }
}
