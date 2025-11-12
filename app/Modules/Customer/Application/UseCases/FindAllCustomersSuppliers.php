<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;

readonly class FindAllCustomersSuppliers
{
    public function __construct(private readonly CustomerRepositoryInterface $customerRepository){}

    public function execute(): array
    {
        return $this->customerRepository->findAllCustomersSuppliers();
    }
}
