<?php

namespace App\Modules\UserAssignment\Domain\Entities;

class UserAssignment
{
    private int $id;
    private int $userId;
    private int $ciaId;
    private int $branchId;
    private ?int $status;

    public function __construct(int $id, int $userId, int $ciaId, int $branchId, ?int $status = 1)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->ciaId = $ciaId;
        $this->branchId = $branchId;
        $this->status = $status;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCiaId(): int
    {
        return $this->ciaId;
    }

    public function getBranchId(): int
    {
        return $this->branchId;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function changeBranch(int $newBranch): void
    {
        $this->branchId = $newBranch;
    }

    public function changeCompany(int $newCompany): void
    {
        $this->ciaId = $newCompany;
    }

    public function updateStatus(int $newStatus): void
    {
        $this->status = $newStatus;
    }

}
