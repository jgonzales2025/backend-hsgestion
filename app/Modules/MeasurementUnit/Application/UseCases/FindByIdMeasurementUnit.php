<?php

namespace App\Modules\MeasurementUnit\Application\UseCases;

use App\Modules\MeasurementUnit\Domain\Entities\MeasurementUnit;
use App\Modules\MeasurementUnit\Domain\Interfaces\MeasurementUnitRepositoryInterface;

class FindByIdMeasurementUnit
{
    private measurementUnitRepositoryInterface $measurementUnitRepository;

    public function __construct(MeasurementUnitRepositoryInterface $measurementUnitRepository)
    {
        $this->measurementUnitRepository = $measurementUnitRepository;
    }

    public function execute(int $id): ?MeasurementUnit
    {
        return $this->measurementUnitRepository->findById($id);
    }
}
