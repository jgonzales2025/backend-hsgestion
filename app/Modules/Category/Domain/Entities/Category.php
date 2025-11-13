<?php

namespace App\Modules\Category\Domain\Entities;

class Category
{
    private int $id;
    private string $name;
    private ?int $status;

    public function __construct(int $id, string $name, ?int $status = 1)
    {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getStatus(): ?int { return $this->status; }
}
