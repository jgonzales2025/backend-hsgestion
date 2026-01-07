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
            parallel_rate: $exchangeRate->parallel_rate,
            almacen: $exchangeRate->almacen,
            compras: $exchangeRate->compras,
            ventas: $exchangeRate->ventas,
            cobranzas: $exchangeRate->cobranzas,
            pagos: $exchangeRate->pagos,
        );
    }

    public function findById(int $id): ?ExchangeRate
    {
        $eloquentExchangeRate = EloquentExchangeRate::find($id);

        if (!$eloquentExchangeRate) {
            return null;
        }

        return new ExchangeRate(
            id: $eloquentExchangeRate->id,
            date: $eloquentExchangeRate->date,
            purchase_rate: $eloquentExchangeRate->purchase_rate,
            sale_rate: $eloquentExchangeRate->sale_rate,
            parallel_rate: $eloquentExchangeRate->parallel_rate
        );
    }

    public function update(ExchangeRate $exchangeRate): ?ExchangeRate
    {
        $eloquentExchangeRate = EloquentExchangeRate::find($exchangeRate->getId());

        if (!$eloquentExchangeRate) {
            return null;
        }

        $eloquentExchangeRate->update([
            'parallel_rate' => $exchangeRate->getParallelRate()
        ]);

        return new ExchangeRate(
            id: $eloquentExchangeRate->id,
            date: $eloquentExchangeRate->date,
            purchase_rate: $eloquentExchangeRate->purchase_rate,
            sale_rate: $eloquentExchangeRate->sale_rate,
            parallel_rate: $eloquentExchangeRate->parallel_rate
        );
    }

    public function findAll(?string $startDate, ?string $endDate)
    {
        $exchangeRates = EloquentExchangeRate::query()
            ->when($startDate, fn($query) => $query->where('date', '>=', $startDate))
            ->when($endDate, fn($query) => $query->where('date', '<=', $endDate))
            ->orderBy('date', 'desc')
            ->paginate(10);

        $exchangeRates->getCollection()->transform(fn($exchangeRate) => new ExchangeRate(
                id: $exchangeRate->id,
                date: $exchangeRate->date,
                purchase_rate: $exchangeRate->purchase_rate,
                sale_rate: $exchangeRate->sale_rate,
                parallel_rate: $exchangeRate->parallel_rate,
                almacen: $exchangeRate->almacen,
                compras: $exchangeRate->compras,
                ventas: $exchangeRate->ventas,
                cobranzas: $exchangeRate->cobranzas,
                pagos: $exchangeRate->pagos,
            ));

        return $exchangeRates;
    }

    public function updateAlmacen(int $id, bool $status): void
    {
        EloquentExchangeRate::where('id', $id)->update([
            'almacen' => $status
        ]);
    }

    public function updateCompras(int $id, bool $status): void
    {
        EloquentExchangeRate::where('id', $id)->update([
            'compras' => $status
        ]);
    }

    public function updateVentas(int $id, bool $status): void
    {
        EloquentExchangeRate::where('id', $id)->update([
            'ventas' => $status
        ]);
    }

    public function updateCobranzas(int $id, bool $status): void
    {
        EloquentExchangeRate::where('id', $id)->update([
            'cobranzas' => $status
        ]);
    }

    public function updatePagos(int $id, bool $status): void
    {
        EloquentExchangeRate::where('id', $id)->update([
            'pagos' => $status
        ]);
    }
}
