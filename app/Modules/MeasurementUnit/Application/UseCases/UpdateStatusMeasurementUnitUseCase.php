<?php

namespace App\Modules\MeasurementUnit\Application\UseCases;

use App\Modules\MeasurementUnit\Domain\Interfaces\MeasurementUnitRepositoryInterface;

class UpdateStatusMeasurementUnitUseCase
{
    private MeasurementUnitRepositoryInterface $measurementUnitRepository;

    public function __construct(MeasurementUnitRepositoryInterface $measurementUnitRepository)
    {
        $this->measurementUnitRepository = $measurementUnitRepository;
    }

    public function execute(int $measurementUnitId, int $status): void
    {
        $this->measurementUnitRepository->updateStatus($measurementUnitId, $status);
    }
}
