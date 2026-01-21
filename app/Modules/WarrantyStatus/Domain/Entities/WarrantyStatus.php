<?php

namespace App\Modules\WarrantyStatus\Domain\Entities;

class WarrantyStatus
{
    public int $id;
    public string $name;
    public string $color;
    public bool $status;

    public function __construct(int $id, string $name, string $color, bool $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->color = $color;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getColor(): string { return $this->color; }
    public function getStatus(): bool { return $this->status; }
}
