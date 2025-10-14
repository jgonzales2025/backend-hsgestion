<?php

namespace App\Modules\ExchangeRate\Domain\Interfaces;

use App\Modules\ExchangeRate\Domain\Entities\ExchangeRate;

interface ExchangeRateRepositoryInterface
{
    public function find(): ?ExchangeRate;

    public function findById(int $id): ?ExchangeRate;

    public function update(ExchangeRate $exchangeRate): ?ExchangeRate;

    public function findAll(): array;
}
