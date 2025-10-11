<?php

namespace App\Modules\BranchPhone\Domain\Entities;

class BranchPhone
{
    private int $id;
    private int $branch_id;
    private string $phone;

    public function __construct(int $id, int $branch_id, string $phone)
    {
        $this->id = $id;
        $this->branch_id = $branch_id;
        $this->phone = $phone;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBranchId(): int
    {
        return $this->branch_id;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
