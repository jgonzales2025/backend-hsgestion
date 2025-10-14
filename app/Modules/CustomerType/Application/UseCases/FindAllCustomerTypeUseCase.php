<?php

namespace App\Modules\CustomerType\Application\UseCases;

use App\Modules\CustomerType\Domain\Interfaces\CustomerTypeRepositoryInterface;

class FindAllCustomerTypeUseCase
{
    private customerTypeRepositoryInterface $customerTypeRepository;

    public function __construct(CustomerTypeRepositoryInterface $customerTypeRepository)
    {
        $this->customerTypeRepository = $customerTypeRepository;
    }

    public function execute(): array
    {
        return $this->customerTypeRepository->findAll();
    }
}
