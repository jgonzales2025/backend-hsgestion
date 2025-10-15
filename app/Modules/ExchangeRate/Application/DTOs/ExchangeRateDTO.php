<?php

namespace App\Modules\ExchangeRate\Application\DTOs;

class ExchangeRateDTO
{
    public $parallel_rate;

    public function __construct(array $data)
    {
        $this->parallel_rate = $data['parallel_rate'];
    }
}
