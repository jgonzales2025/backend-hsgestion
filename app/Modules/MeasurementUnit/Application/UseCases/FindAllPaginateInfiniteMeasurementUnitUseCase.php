<?php

namespace App\Modules\MeasurementUnit\Application\UseCases;

use App\Modules\MeasurementUnit\Domain\Interfaces\MeasurementUnitRepositoryInterface;

class FindAllPaginateInfiniteMeasurementUnitUseCase
{
    public function __construct(private readonly MeasurementUnitRepositoryInterface $measurementUnitRepository)
    {
    }

    public function execute(?string $description)
    {
        return $this->measurementUnitRepository->findAllPaginateInfinite($description);
    }
}