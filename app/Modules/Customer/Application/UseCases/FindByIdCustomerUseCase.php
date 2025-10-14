<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;

readonly class FindByIdCustomerUseCase
{
    public function __construct(private readonly CustomerRepositoryInterface $customerRepository){}

    public function execute($id): ?Customer
    {
        return $this->customerRepository->findById($id);
    }
}
