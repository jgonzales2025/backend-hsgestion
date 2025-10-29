<?php

namespace App\Modules\MeasurementUnit\Infrastructure\Persistence;

use App\Modules\MeasurementUnit\Domain\Entities\MeasurementUnit;
use App\Modules\MeasurementUnit\Domain\Interfaces\MeasurementUnitRepositoryInterface;
use App\Modules\MeasurementUnit\Infrastructure\Models\EloquentMeasurementUnit;

class EloquentMeasurementUnitRepository implements MeasurementUnitRepositoryInterface
{

    public function findAll(): array
    {
        $measurementUnits = EloquentMeasurementUnit::all()->sortByDesc('created_at');

        if ($measurementUnits->isEmpty()) {
            return [];
        }

        return $measurementUnits->map(fn ($eloquentMeasurementUnit) => $this->mapToEntity($eloquentMeasurementUnit))->toArray();
    }

    public function save(MeasurementUnit $measurementUnit): MeasurementUnit
    {
        $eloquentMeasurementUnit = EloquentMeasurementUnit::create([
            'name' => $measurementUnit->getName(),
            'abbreviation' => $measurementUnit->getAbbreviation(),
            'status' => $measurementUnit->getStatus(),
        ]);

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
            'abbreviation' => $measurementUnit->getAbbreviation(),
            'status' => $measurementUnit->getStatus(),
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
}
