<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;

readonly class FindAllCustomersExcludingCompaniesUseCase
{
    public function __construct(private readonly CustomerRepositoryInterface $customerRepository){}

    public function execute(?string $customerName, ?int $status, ?int $documentTypeId, ?int $recordTypeId)
    {
        return $this->customerRepository->findAllCustomerExceptionCompanies($customerName, $status, $documentTypeId, $recordTypeId);
    }
}
