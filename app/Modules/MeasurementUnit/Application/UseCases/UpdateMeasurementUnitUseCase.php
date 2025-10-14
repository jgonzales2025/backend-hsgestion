<?php

namespace App\Modules\MeasurementUnit\Application\UseCases;

use App\Modules\MeasurementUnit\Application\DTOs\MeasurementUnitDTO;
use App\Modules\MeasurementUnit\Domain\Entities\MeasurementUnit;
use App\Modules\MeasurementUnit\Domain\Interfaces\MeasurementUnitRepositoryInterface;

class UpdateMeasurementUnitUseCase
{
    private MeasurementUnitRepositoryInterface $measurementUnitRepository;

    public function __construct(MeasurementUnitRepositoryInterface $measurementUnitRepository)
    {
        $this->measurementUnitRepository = $measurementUnitRepository;
    }

    public function execute(int $id, MeasurementUnitDTO $measurementUnitDTO): ?MeasurementUnit
    {
        $updatedMeasurementUnit = new MeasurementUnit(
            $id,
            $measurementUnitDTO->name,
            $measurementUnitDTO->abbreviation,
            $measurementUnitDTO->status
        );

        return $this->measurementUnitRepository->update($updatedMeasurementUnit);
    }
}
