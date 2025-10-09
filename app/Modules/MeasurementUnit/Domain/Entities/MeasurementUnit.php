<?php

namespace App\Modules\MeasurementUnit\Domain\Entities;

class MeasurementUnit
{
    private int $id;
    private string $name;
    private string $abbreviation;
    private int $status;

    public function __construct(int $id, string $name, string $abbreviation, int $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->abbreviation = $abbreviation;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getAbbreviation(): string { return $this->abbreviation; }
    public function getStatus(): int { return $this->status; }
}
