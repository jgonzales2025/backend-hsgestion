<?php

namespace App\Modules\PaymentMethod\Domain\Entities;
use http\Exception\InvalidArgumentException;

class PaymentMethod
{
    private int $id;
    private string $description;
    private string $status;

    public function __construct(int $id, string $description, bool $status)
    {
        $this->id = $id;
        $this->description = $description;
        $this->status = $status;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}