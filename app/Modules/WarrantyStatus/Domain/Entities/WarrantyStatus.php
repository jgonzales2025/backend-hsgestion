<?php

namespace App\Modules\WarrantyStatus\Domain\Entities;

class WarrantyStatus
{
    public int $id;
    public string $name;
    public bool $status;

    public function __construct(int $id, string $name, bool $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getStatus(): bool { return $this->status; }
}
