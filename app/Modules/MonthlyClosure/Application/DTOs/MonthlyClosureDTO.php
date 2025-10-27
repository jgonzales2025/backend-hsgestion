<?php

namespace App\Modules\MonthlyClosure\Application\DTOs;

class MonthlyClosureDTO
{
    public int $year;
    public int $month;

    public function __construct(array $data)
    {
        $this->year = $data['year'];
        $this->month = $data['month'];
    }
}
