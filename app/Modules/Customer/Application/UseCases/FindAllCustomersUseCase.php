<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;

readonly class FindAllCustomersUseCase
{
    public function __construct(private CustomerRepositoryInterface $customerRepository) {}

    public function execute(?string $customerName, ?string $documentNumber): array
    {
        return $this->customerRepository->findAll($customerName, $documentNumber);
    }
}
