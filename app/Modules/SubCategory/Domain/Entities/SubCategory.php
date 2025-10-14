<?php

namespace App\Modules\SubCategory\Domain\Entities;

class SubCategory
{
    private int $id;
    private string $name;
    private int $category_id;
    private ?string $category_name;
    private int $status;

    public function __construct(int $id, string $name, int $category_id, ?string $category_name, int $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->category_id = $category_id;
        $this->category_name = $category_name;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string|null { return $this->name; }
    public function getCategoryId(): int { return $this->category_id; }
    public function getCategoryName(): string { return $this->category_name; }
    public function getStatus(): int { return $this->status; }
}
