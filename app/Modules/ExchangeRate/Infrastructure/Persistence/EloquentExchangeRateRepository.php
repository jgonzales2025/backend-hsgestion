<?php

namespace App\Modules\ExchangeRate\Infrastructure\Persistence;

use App\Modules\ExchangeRate\Domain\Entities\ExchangeRate;
use App\Modules\ExchangeRate\Domain\Interfaces\ExchangeRateRepositoryInterface;
use App\Modules\ExchangeRate\Infrastructure\Models\EloquentExchangeRate;

class EloquentExchangeRateRepository implements ExchangeRateRepositoryInterface
{

    public function find(): ?ExchangeRate
    {
        $exchangeRate = EloquentExchangeRate::all()->sortByDesc('date')->first();

        if ($exchangeRate === null) {
            return null;
        }

        return new ExchangeRate(
            id: $exchangeRate->id,
            date: $exchangeRate->date,
            purchase_rate: $exchangeRate->purchase_rate,
            sale_rate: $exchangeRate->sale_rate,
            parallel_rate: $exchangeRate->parallel_rate
        );
    }
}
