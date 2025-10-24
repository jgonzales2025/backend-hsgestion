<?php

namespace App\Modules\TransactionLog\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\DocumentType\Domain\Entities\DocumentType;
use App\Modules\User\Domain\Entities\User;

class TransactionLog
{
    private User $user;
    private ?int $roleId;
    private string $role_name;
    private string $description_log;
    private string $action;
    private Company $company;
    private Branch $branch;
    private DocumentType $documentType;
    private string $serie;
    private string $correlative;
    private string $ipAddress;
    private ?string $userAgent;

    public function __construct(User $user, ?int $roleId, string $role_name, string $description_log, string $action, Company $company, Branch $branch, DocumentType $documentType, string $serie, string $correlative, string $ipAddress, ?string $userAgent)
    {
        $this->user = $user;
        $this->roleId = $roleId;
        $this->role_name = $role_name;
        $this->description_log = $description_log;
        $this->action = $action;
        $this->company = $company;
        $this->branch = $branch;
        $this->documentType = $documentType;
        $this->serie = $serie;
        $this->correlative = $correlative;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }

    public function getUser(): User { return $this->user; }
    public function getRoleId(): ?int { return $this->roleId; }
    public function getRoleName(): string { return $this->role_name; }
    public function getDescriptionLog(): string { return $this->description_log; }
    public function getAction(): string { return $this->action; }
    public function getCompany(): Company { return $this->company; }
    public function getBranch(): Branch { return $this->branch; }
    public function getDocumentType(): DocumentType { return $this->documentType; }
    public function getSerie(): string { return $this->serie; }
    public function getCorrelative(): string { return $this->correlative; }
    public function getIpAddress(): string { return $this->ipAddress; }
    public function getUserAgent(): ?string { return $this->userAgent; }
}
