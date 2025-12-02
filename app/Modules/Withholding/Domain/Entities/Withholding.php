<?php

namespace App\Modules\Withholding\Domain\Entities;

class Withholding
{
    public int $id;
    public string $date;
    public float $percentage;

    public function __construct(
        int $id,
        string $date,
        float $percentage
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->percentage = $percentage;
    }

    public function getId(): int { return $this->id; }
    public function getDate(): string { return $this->date; }
    public function getPercentage(): float { return $this->percentage; }
}