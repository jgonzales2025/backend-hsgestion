<?php

namespace App\Modules\MeasurementUnit\Infrastructure\Persistence;

use App\Modules\MeasurementUnit\Domain\Entities\MeasurementUnit;
use App\Modules\MeasurementUnit\Domain\Interfaces\MeasurementUnitRepositoryInterface;
use App\Modules\MeasurementUnit\Infrastructure\Models\EloquentMeasurementUnit;

class EloquentMeasurementUnitRepository implements MeasurementUnitRepositoryInterface
{

    public function findAllPaginateInfinite()
    {
        return EloquentMeasurementUnit::query()
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->cursorPaginate(10);
    }

    public function findAll(?string $description, ?int $status)
    {
        $measurementUnits = EloquentMeasurementUnit::query()
            ->when($description, function ($query) use ($description) {
                $query->where(function ($q) use ($description) {
                    $q->where('name', 'like', "%{$description}%")
                        ->orWhere('abbreviation', 'like', "%{$description}%");
                });
            })
            ->when($status !== null, fn($query) => $query->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $measurementUnits->getCollection()->transform(fn($measurementUnit) => $this->mapToEntity($measurementUnit));

        return $measurementUnits;
    }

    public function save(MeasurementUnit $measurementUnit): MeasurementUnit
    {
        $eloquentMeasurementUnit = EloquentMeasurementUnit::create([
            'name' => $measurementUnit->getName(),
            'abbreviation' => $measurementUnit->getAbbreviation()
        ]);
        $eloquentMeasurementUnit->refresh();

        return $this->mapToEntity($eloquentMeasurementUnit);
    }

    public function findById(int $id): ?MeasurementUnit
    {
        $eloquentMeasurementUnit = EloquentMeasurementUnit::find($id);

        if (!$eloquentMeasurementUnit) {
            return null;
        }

        return $this->mapToEntity($eloquentMeasurementUnit);
    }

    public function update(MeasurementUnit $measurementUnit): MeasurementUnit
    {
        $eloquentMeasurementUnit = EloquentMeasurementUnit::find($measurementUnit->getId());

        $eloquentMeasurementUnit->update([
            'name' => $measurementUnit->getName(),
            'abbreviation' => $measurementUnit->getAbbreviation()
        ]);

        return $this->mapToEntity($eloquentMeasurementUnit);
    }

    private function mapToEntity($eloquentMeasurementUnit): MeasurementUnit
    {
        return new MeasurementUnit(
            id: $eloquentMeasurementUnit->id,
            name: $eloquentMeasurementUnit->name,
            abbreviation: $eloquentMeasurementUnit->abbreviation,
            status: $eloquentMeasurementUnit->status,
        );
    }

    public function updateStatus(int $measurementUnitId, int $status): void
    {
        EloquentMeasurementUnit::where('id', $measurementUnitId)->update(['status' => $status]);
    }
}
