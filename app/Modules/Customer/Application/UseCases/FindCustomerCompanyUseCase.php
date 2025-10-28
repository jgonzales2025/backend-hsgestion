<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;

readonly class FindCustomerCompanyUseCase
{
    public function __construct(private readonly CustomerRepositoryInterface $customerRepository) {}

    public function execute(): ?Customer
    {
        return $this->customerRepository->findCustomerCompany();
    }
}
