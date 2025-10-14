<?php

namespace App\Modules\EmissionReason\Domain\Entities;

class EmissionReason
{
    private int $id;
    private string $description;
    private int $status;

    public function __construct(int $id, string $description, int $status)
    {
        $this->id = $id;
        $this->description = $description;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getDescription(): string { return $this->description; }
    public function getStatus(): int { return $this->status; }
}
