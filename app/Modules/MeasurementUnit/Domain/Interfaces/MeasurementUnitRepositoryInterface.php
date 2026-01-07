<?php

namespace App\Modules\MeasurementUnit\Domain\Interfaces;

use App\Modules\MeasurementUnit\Domain\Entities\MeasurementUnit;

interface MeasurementUnitRepositoryInterface
{
    public function findAll(?string $description, ?int $status);

    public function save(MeasurementUnit $measurementUnit): MeasurementUnit;

    public function findById(int $id): ?MeasurementUnit;

    public function update(MeasurementUnit $measurementUnit): MeasurementUnit;

    public function updateStatus(int $measurementUnitId, int $status): void;
    public function findAllPaginateInfinite(?string $description);
}
