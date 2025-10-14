<?php

namespace App\Modules\Driver\Application\UseCases;

use App\Modules\Driver\Domain\Interfaces\DriverRepositoryInterface;

class FindByIdDriverUseCase
{
    private driverRepositoryInterface $driverRepository;

    public function __construct(driverRepositoryInterface $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function execute(int $id)
    {
        return $this->driverRepository->findById($id);
    }
}
