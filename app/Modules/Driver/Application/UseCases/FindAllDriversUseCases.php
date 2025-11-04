<?php

namespace App\Modules\Driver\Application\UseCases;

use App\Modules\Driver\Domain\Interfaces\DriverRepositoryInterface;

class FindAllDriversUseCases
{
    private driverRepositoryInterface $driverRepository;

    public function __construct(DriverRepositoryInterface $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function execute(?string $description): array
    {
        return $this->driverRepository->findAllDrivers($description);
    }
}
