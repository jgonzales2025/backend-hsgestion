<?php

namespace App\Modules\PettyCashMotive\Domain\Entities;

use App\Modules\DocumentType\Domain\Entities\DocumentType;

class PettyCashMotive
{
    private ?int $id;
    private int $company_id;
    private string $description;
    private ?DocumentType $receipt_type;
    private int $user_id;
    private bool $status;

    public function __construct(
        ?int $id,
        int $company_id,
        string $description,
        ?DocumentType $receipt_type,
        int $user_id,
        bool $status
    ) {
        $this->id = $id;
        $this->company_id = $company_id;
        $this->description = $description;
        $this->receipt_type = $receipt_type;
        $this->user_id = $user_id;
        $this->status = $status;
    }
    public function getId(): int|null
    {
        return $this->id;
    }
    public function getCompanyId()
    {
        return $this->company_id;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getReceiptType(): DocumentType|null
    {
        return $this->receipt_type;
    }
    public function getUserId()
    {
        return $this->user_id;
    }
    public function getStatus()
    {
        return $this->status;
    }
}
