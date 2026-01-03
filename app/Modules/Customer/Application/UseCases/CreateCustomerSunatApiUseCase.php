<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Application\DTOs\CustomerDTO;
use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\CustomerDocumentType\Application\UseCases\FindByIdCustomerDocumentTypeUseCase;
use App\Modules\CustomerDocumentType\Domain\Interfaces\CustomerDocumentTypeRepositoryInterface;

readonly class CreateCustomerSunatApiUseCase
{

    public function __construct(private readonly customerRepositoryInterface $customerRepository,
    private readonly CustomerDocumentTypeRepositoryInterface $customerDocumentTypeRepository){}

    public function execute(CustomerDTO $customerDTO, string $document): Customer
    {

        $customerDocumentTypeUseCases = new FindByIdCustomerDocumentTypeUseCase($this->customerDocumentTypeRepository);
        $customerDocumentType = $customerDocumentTypeUseCases->execute($customerDTO->customer_document_type_id);

        $customer = new Customer(
            id: 0,
            record_type_id: $customerDTO->record_type_id,
            record_type_name: null,
            customer_document_type: $customerDocumentType,
            document_number: $customerDTO->document_number,
            company_name: $customerDTO->company_name,
            name: $customerDTO->name,
            lastname: $customerDTO->lastname,
            second_lastname: $customerDTO->second_lastname,
            customer_type_id: $customerDTO->customer_type_id,
            customer_type_name: null,
            contact: $customerDTO->contact,
            is_withholding_applicable: $customerDTO->is_withholding_applicable,
            phones: null,
            emails: null,
            addresses: null,
        );

        return $this->customerRepository->saveCustomerBySunatApi($customer, $document);
    }
}
