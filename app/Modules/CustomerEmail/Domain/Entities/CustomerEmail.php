<?php

namespace App\Modules\CustomerEmail\Domain\Entities;

class CustomerEmail
{
    private int $id;
    private string $email;
    private int $customer_id;
    private ?int $status;

    public function __construct(int $id, string $email, int $customer_id, ?int $status)
    {
        $this->id = $id;
        $this->email = $email;
        $this->customer_id = $customer_id;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getEmail(): string { return $this->email; }
    public function getCustomerId(): int { return $this->customer_id; }
    public function getStatus(): ?int { return $this->status; }
}
