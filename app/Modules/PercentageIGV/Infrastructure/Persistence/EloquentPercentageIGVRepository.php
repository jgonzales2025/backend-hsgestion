<?php

namespace App\Modules\PercentageIGV\Infrastructure\Persistence;

use App\Modules\PercentageIGV\Domain\Entities\PercentageIGV;
use App\Modules\PercentageIGV\Domain\Interfaces\PercentageIGVRepositoryInterface;
use App\Modules\PercentageIGV\Infrastructure\Models\EloquentPercentageIGV;

class EloquentPercentageIGVRepository implements PercentageIGVRepositoryInterface
{

    public function findAll()
    {
        $percentages = EloquentPercentageIGV::query()->orderBy('date', 'desc')->paginate(10);

        $percentages->getCollection()->transform(fn($percentage) => new PercentageIGV(
                id: $percentage->id,
                date: new \DateTimeImmutable($percentage->date),
                percentage: $percentage->percentage,
            ));
        return $percentages;
    }

    public function save(PercentageIGV $percentageIGV): ?PercentageIGV
    {
        $eloquentPercentageIGV = EloquentPercentageIGV::create([
            'date' => $percentageIGV->getDate()->format('Y-m-d'),
            'percentage' => $percentageIGV->getPercentage(),
        ]);

        return new PercentageIGV(
            id: $eloquentPercentageIGV->id,
            date: new \DateTimeImmutable($eloquentPercentageIGV->date),
            percentage: $eloquentPercentageIGV->percentage,
        );
    }

    public function findById(int $id): ?PercentageIGV
    {
        $eloquentPercentageIGV = EloquentPercentageIGV::find($id);

        if (!$eloquentPercentageIGV) {
            return null;
        }

        return new PercentageIGV(
            id: $eloquentPercentageIGV->id,
            date: new \DateTimeImmutable($eloquentPercentageIGV->date),
            percentage: $eloquentPercentageIGV->percentage,
        );
    }

    public function findPercentageCurrent(): ?PercentageIGV
    {
        $percentageIGV = EloquentPercentageIGV::orderBy('date', 'desc')->first();
        if (!$percentageIGV) {
            return null;
        }
        return new PercentageIGV(
            id: $percentageIGV->id,
            date: new \DateTimeImmutable($percentageIGV->date),
            percentage: $percentageIGV->percentage,
        );
    }

    public function update(PercentageIGV $percentageIGV): ?PercentageIGV
    {
        $eloquentPercentageIGV = EloquentPercentageIGV::find($percentageIGV->getId());

        if (!$eloquentPercentageIGV) {
            return null;
        }

        $eloquentPercentageIGV->update([
            'date' => $percentageIGV->getDate()->format('Y-m-d'),
            'percentage' => $percentageIGV->getPercentage(),
        ]);

        return new PercentageIGV(
            id: $eloquentPercentageIGV->id,
            date: new \DateTimeImmutable($eloquentPercentageIGV->date),
            percentage: $eloquentPercentageIGV->percentage,
        );
    }
}
