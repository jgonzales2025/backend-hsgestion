<?php

namespace App\Modules\PaymentConcept\Domain\Entities;

class PaymentConcept
{
    public int $id;
    public string $description;
    public ?bool $status;
    public ?int $company_id;

    public function __construct(
        int $id,
        string $description,
        ?bool $status = null,
        ?int $company_id = null
    ) {
        $this->id = $id;
        $this->description = $description;
        $this->status = $status;
        $this->company_id = $company_id;
    }

    public function getId(): int { return $this->id; }
    public function getDescription(): string { return $this->description; }
    public function getStatus(): ?bool { return $this->status; }
    public function getCompanyId(): ?int { return $this->company_id; }
}