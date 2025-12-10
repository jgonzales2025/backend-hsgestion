<?php

namespace App\Modules\PaymentConcept\Domain\Entities;

class PaymentConcept
{
    public int $id;
    public string $description;
    public ?bool $status;

    public function __construct(
        int $id,
        string $description,
        ?bool $status = null
    ) {
        $this->id = $id;
        $this->description = $description;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getDescription(): string { return $this->description; }
    public function getStatus(): ?bool { return $this->status; }
}