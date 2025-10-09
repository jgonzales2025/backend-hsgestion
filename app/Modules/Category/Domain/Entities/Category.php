<?php

namespace App\Modules\Category\Domain\Entities;

class Category
{
    private int $id;
    private string $name;
    private string $status;

    public function __construct(int $id, string $name, string $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getStatus(): string { return $this->status; }
}
