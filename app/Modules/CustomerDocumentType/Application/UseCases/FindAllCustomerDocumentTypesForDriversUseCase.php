<?php

namespace App\Modules\CustomerDocumentType\Application\UseCases;

use App\Modules\CustomerDocumentType\Domain\Interfaces\CustomerDocumentTypeRepositoryInterface;

class FindAllCustomerDocumentTypesForDriversUseCase
{
    private CustomerDocumentTypeRepositoryInterface $customerDocumentTypeRepository;

    public function __construct(CustomerDocumentTypeRepositoryInterface $customerDocumentTypeRepository)
    {
        $this->customerDocumentTypeRepository = $customerDocumentTypeRepository;
    }

    public function execute(): array
    {
        return $this->customerDocumentTypeRepository->findAllForDrivers();
    }
}
