<?php

namespace App\Modules\CustomerDocumentType\Application\UseCases;

use App\Modules\CustomerDocumentType\Domain\Entities\CustomerDocumentType;
use App\Modules\CustomerDocumentType\Domain\Interfaces\CustomerDocumentTypeRepositoryInterface;

class FindByIdCustomerDocumentTypeUseCase
{
    private CustomerDocumentTypeRepositoryInterface $customerDocumentTypeRepository;

    public function __construct(CustomerDocumentTypeRepositoryInterface $customerDocumentTypeRepository)
    {
        $this->customerDocumentTypeRepository = $customerDocumentTypeRepository;
    }

    public function execute(int $id): CustomerDocumentType
    {
        return $this->customerDocumentTypeRepository->findById($id);
    }
}