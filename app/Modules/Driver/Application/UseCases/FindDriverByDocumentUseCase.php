<?php

namespace App\Modules\Driver\Application\UseCases;

use App\Modules\Driver\Domain\Entities\Driver;
use App\Modules\Driver\Domain\Interfaces\DriverRepositoryInterface;



 class  FindDriverByDocumentUseCase
{
    public function __construct(private readonly DriverRepositoryInterface $customerRepository){}

    public function execute(string $documentNumber): ?Driver
    {
        return $this->customerRepository->findDriverByDocumentNumber($documentNumber);
    }
}
