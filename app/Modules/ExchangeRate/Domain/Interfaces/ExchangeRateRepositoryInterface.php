<?php

namespace App\Modules\ExchangeRate\Domain\Interfaces;

use App\Modules\ExchangeRate\Domain\Entities\ExchangeRate;

interface ExchangeRateRepositoryInterface
{
    public function find(): ?ExchangeRate;
}
