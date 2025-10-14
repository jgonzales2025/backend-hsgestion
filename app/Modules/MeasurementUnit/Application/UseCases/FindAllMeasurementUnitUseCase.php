<?php

namespace App\Modules\MeasurementUnit\Application\UseCases;

use App\Modules\MeasurementUnit\Domain\Interfaces\MeasurementUnitRepositoryInterface;

class FindAllMeasurementUnitUseCase
{
    private measurementUnitRepositoryInterface $measurementUnitRepository;

    public function __construct(MeasurementUnitRepositoryInterface $measurementUnitRepository)
    {
        $this->measurementUnitRepository = $measurementUnitRepository;
    }

    public function execute(): array
    {
        return $this->measurementUnitRepository->findAll();
    }
}
