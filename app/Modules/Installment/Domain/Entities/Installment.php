<?php

namespace App\Modules\Installment\Domain\Entities;

class Installment
{
    public int $id;
    public int $installment_number;
    public int $sale_id;
    public float $amount;
    public string $due_date;

    public function __construct(int $id, int $installment_number, int $sale_id, float $amount, string $due_date)
    {
        $this->id = $id;
        $this->installment_number = $installment_number;
        $this->sale_id = $sale_id;
        $this->amount = $amount;
        $this->due_date = $due_date;
    }

    public function getId(): int { return $this->id; }
    public function getInstallmentNumber(): int { return $this->installment_number; }
    public function getSaleId(): int { return $this->sale_id; }
    public function getAmount(): float { return $this->amount; }
    public function getDueDate(): string { return $this->due_date; }
}