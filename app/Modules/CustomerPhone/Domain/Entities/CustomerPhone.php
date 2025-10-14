<?php

namespace App\Modules\CustomerPhone\Domain\Entities;

class CustomerPhone
{
    private ?int $id;
    private string $phone;
    private int $customer_id;
    private ?int $status;

    public function __construct(?int $id, string $phone, int $customer_id, ?int $status)
    {
        $this->id = $id;
        $this->phone = $phone;
        $this->customer_id = $customer_id;
        $this->status = $status;
    }

    public function getId(): int|null { return $this->id; }
    public function getPhone(): string { return $this->phone; }
    public function getCustomerId(): int { return $this->customer_id; }
    public function getStatus(): int | null { return $this->status; }
}
