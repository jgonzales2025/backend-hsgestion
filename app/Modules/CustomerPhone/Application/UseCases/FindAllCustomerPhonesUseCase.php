<?php

namespace App\Modules\CustomerPhone\Application\UseCases;

use App\Modules\CustomerPhone\Domain\Interfaces\CustomerPhoneRepositoryInterface;

class FindAllCustomerPhonesUseCase
{
    private CustomerPhoneRepositoryInterface $customerPhoneRepository;

    public function __construct(CustomerPhoneRepositoryInterface $customerPhoneRepository)
    {
        $this->customerPhoneRepository = $customerPhoneRepository;
    }

    public function execute(): array
    {
        return $this->customerPhoneRepository->findAll();
    }
}
