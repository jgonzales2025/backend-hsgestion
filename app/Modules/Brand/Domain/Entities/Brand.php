<?php

namespace App\Modules\Brand\Domain\Entities;

class Brand
{
    private int $id;
    private string $name;
    private string $status;

    public function __construct(int $id, string $name, bool $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getStatus(): bool {
        return $this->status;
    }
}
