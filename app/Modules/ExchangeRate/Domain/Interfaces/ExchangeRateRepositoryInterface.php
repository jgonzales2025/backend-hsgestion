<?php

namespace App\Modules\ExchangeRate\Domain\Interfaces;

use App\Modules\ExchangeRate\Domain\Entities\ExchangeRate;

interface ExchangeRateRepositoryInterface
{
    public function find(): ?ExchangeRate;

    public function findById(int $id): ?ExchangeRate;

    public function update(ExchangeRate $exchangeRate): ?ExchangeRate;

    public function findAll(string $startDate, string $endDate);

    public function updateAlmacen(int $id, bool $status): void;

    public function updateCompras(int $id, bool $status): void;

    public function updateVentas(int $id, bool $status): void;

    public function updateCobranzas(int $id, bool $status): void;

    public function updatePagos(int $id, bool $status): void;
}
