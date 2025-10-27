<?php

namespace App\Modules\MonthlyClosure\Infrastructure\Persistence;

use App\Modules\MonthlyClosure\Domain\Entities\MonthlyClosure;
use App\Modules\MonthlyClosure\Domain\Interfaces\MonthlyClosureRepositoryInterface;
use App\Modules\MonthlyClosure\Infrastructure\Models\EloquentMonthlyClosure;
use App\Modules\Sale\Infrastructure\Models\EloquentSale;

class EloquentMonthlyClosureRepository implements MonthlyClosureRepositoryInterface
{

    public function findAll(): ?array
    {
        $eloquentMonthlyClosures = EloquentMonthlyClosure::all()->sortByDesc('created_at');

        if ($eloquentMonthlyClosures->isEmpty()) {
            return [];
        }

        return $eloquentMonthlyClosures->map(function ($eloquentMonthlyClosure){
            return new MonthlyClosure(
                id: $eloquentMonthlyClosure->id,
                year: $eloquentMonthlyClosure->year,
                month: $eloquentMonthlyClosure->month,
                st_purchases: $eloquentMonthlyClosure->st_purchases,
                st_sales: $eloquentMonthlyClosure->st_sales,
                st_cash: $eloquentMonthlyClosure->st_cash,
            );
        })->toArray();

    }

    public function save(MonthlyClosure $monthlyClosure): ?MonthlyClosure
    {
        $eloquentMonthlyClosure = EloquentMonthlyClosure::create([
            'year' => $monthlyClosure->getYear(),
            'month' => $monthlyClosure->getMonth(),
            'st_purchases' => $monthlyClosure->getStPurchases(),
            'st_sales' => $monthlyClosure->getStSales(),
            'st_cash' => $monthlyClosure->getStCash(),
        ]);

        return new MonthlyClosure(
            id: $eloquentMonthlyClosure->id,
            year: $eloquentMonthlyClosure->year,
            month: $eloquentMonthlyClosure->month,
            st_purchases: $eloquentMonthlyClosure->st_purchases,
            st_sales: $eloquentMonthlyClosure->st_sales,
            st_cash: $eloquentMonthlyClosure->st_cash,
        );
    }

    public function findById(int $id): ?MonthlyClosure
    {
        $eloquentMonthlyClosure = EloquentMonthlyClosure::find($id);

        if (!$eloquentMonthlyClosure) {
            return null;
        }

        return new MonthlyClosure(
            id: $eloquentMonthlyClosure->id,
            year: $eloquentMonthlyClosure->year,
            month: $eloquentMonthlyClosure->month,
            st_purchases: $eloquentMonthlyClosure->st_purchases,
            st_sales: $eloquentMonthlyClosure->st_sales,
            st_cash: $eloquentMonthlyClosure->st_cash
        );
    }

    public function updateStSales(int $id, int $status): void
    {
        $eloquentMonthlyClosure = EloquentMonthlyClosure::find($id);
        $eloquentMonthlyClosure->st_sales = $status;
        $eloquentMonthlyClosure->save();

        $eloquentSales = EloquentSale::whereMonth('date', $eloquentMonthlyClosure->month)->get();

        foreach ($eloquentSales as $eloquentSale) {
            $eloquentSale->is_locked = $status;
            $eloquentSale->save();
        }
    }
}
