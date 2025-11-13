<?php

namespace App\Modules\Driver\Application\UseCases;

use App\Modules\Driver\Application\DTOs\DriverDTO;
use App\Modules\Driver\Domain\Entities\Driver;
use App\Modules\Driver\Domain\Interfaces\DriverRepositoryInterface;

class UpdateDriverUseCase
{
    private driverRepositoryInterface $driverRepository;

    public function __construct(DriverRepositoryInterface $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function execute(int $id, DriverDTO $driverDTO)
    {
        $existingDriver = $this->driverRepository->findById($id);

        if (!$existingDriver) {
            return null;
        }

        $driver = new Driver(
            id: $id,
            customer_document_type_id: $driverDTO->customer_document_type_id,
            doc_number: $driverDTO->doc_number,
            name: $driverDTO->name,
            pat_surname: $driverDTO->pat_surname,
            mat_surname: $driverDTO->mat_surname,
            license: $driverDTO->license,
            document_type_name: null
        );

        $this->driverRepository->update($driver);
    }
}
