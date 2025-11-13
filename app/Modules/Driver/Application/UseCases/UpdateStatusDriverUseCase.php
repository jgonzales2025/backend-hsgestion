<?php

namespace App\Modules\Driver\Application\UseCases;

use App\Modules\Driver\Domain\Interfaces\DriverRepositoryInterface;

class UpdateStatusDriverUseCase
{
    private DriverRepositoryInterface $driverRepository;

    public function __construct(DriverRepositoryInterface $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function execute(int $driverId, int $status): void
    {
        $this->driverRepository->updateStatus($driverId, $status);
    }
}
