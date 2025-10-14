<?php

namespace App\Modules\PercentageIGV\Domain\Entities;

use DateTimeImmutable;
class PercentageIGV
{
    private int $id;
    private DateTimeImmutable $date;
    private int $percentage;

    public function __construct(int $id, DateTimeImmutable $date, int $percentage)
    {
        $this->id = $id;
        $this->date = $date;
        $this->percentage = $percentage;
    }

    public function getId(): int { return $this->id; }
    public function getDate(): DateTimeImmutable { return $this->date; }
    public function getPercentage(): int { return $this->percentage; }
}
