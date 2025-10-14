<?php

namespace App\Modules\MeasurementUnit\Application\UseCases;

use App\Modules\MeasurementUnit\Application\DTOs\MeasurementUnitDTO;
use App\Modules\MeasurementUnit\Domain\Entities\MeasurementUnit;
use App\Modules\MeasurementUnit\Domain\Interfaces\MeasurementUnitRepositoryInterface;

class CreateMeasurementUnitUseCase
{
    private measurementUnitRepositoryInterface $measurementUnitRepository;

    public function __construct(MeasurementUnitRepositoryInterface $repository)
    {
        $this->measurementUnitRepository = $repository;
    }

    public function execute(MeasurementUnitDTO $measurementUnitDTO): MeasurementUnit
    {
        $measurementUnit = new MeasurementUnit(
            id: 0,
            name: $measurementUnitDTO->name,
            abbreviation: $measurementUnitDTO->abbreviation,
            status: $measurementUnitDTO->status,
        );

        return $this->measurementUnitRepository->save($measurementUnit);
    }
}
