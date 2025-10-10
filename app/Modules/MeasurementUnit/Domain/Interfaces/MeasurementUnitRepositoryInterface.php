<?php

namespace App\Modules\MeasurementUnit\Domain\Interfaces;

use App\Modules\MeasurementUnit\Domain\Entities\MeasurementUnit;

interface MeasurementUnitRepositoryInterface
{
    public function findAll(): array;

    public function save(MeasurementUnit $measurementUnit): MeasurementUnit;

    public function findById(int $id): ?MeasurementUnit;

    public function update(MeasurementUnit $measurementUnit): MeasurementUnit;
}
