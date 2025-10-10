<?php

namespace App\Modules\PercentageIGV\Application\DTOs;

use DateTimeImmutable;
class PercentageIGVDTO
{
    public DateTimeImmutable $date;
    public int $percentage;

    public function __construct(array $data)
    {
        $this->date = new DateTimeImmutable($data['date']);
        $this->percentage = $data['percentage'];
    }
}
