<?php

namespace App\Modules\WarrantyStatus\Domain\Entities;

class WarrantyStatus
{
    public int $id;
    public string $name;
    public string $color;
    public bool $status;
    public int $st_warranty;
    public int $st_support;

    public function __construct(int $id, string $name, string $color, bool $status, int $st_warranty, int $st_support)
    {
        $this->id = $id;
        $this->name = $name;
        $this->color = $color;
        $this->status = $status;
        $this->st_warranty = $st_warranty;
        $this->st_support = $st_support;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getColor(): string { return $this->color; }
    public function getStatus(): bool { return $this->status; }
    public function getStWarranty(): int { return $this->st_warranty; }
    public function getStSupport(): int { return $this->st_support; }
}
