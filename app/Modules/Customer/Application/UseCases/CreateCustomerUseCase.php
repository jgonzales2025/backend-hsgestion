<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Application\DTOs\CustomerDTO;
use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;

class CreateCustomerUseCase
{
    private customerRepositoryInterface $customerRepository;

    public function __construct(customerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function execute(CustomerDTO $customerDTO): Customer
    {
        $customer = new Customer(
            id: 0,
            record_type_id: $customerDTO->record_type_id,
            record_type_name: null,
            customer_document_type_id: $customerDTO->customer_document_type_id,
            customer_document_type_name: null,
            customer_document_type_abbreviation: null,
            document_number: $customerDTO->document_number,
            company_name: $customerDTO->company_name,
            name: $customerDTO->name,
            lastname: $customerDTO->lastname,
            second_lastname: $customerDTO->second_lastname,
            customer_type_id: $customerDTO->customer_type_id,
            customer_type_name: null,
            fax: $customerDTO->fax,
            contact: $customerDTO->contact,
            is_withholding_applicable: $customerDTO->is_withholding_applicable,
            status: $customerDTO->status
        );

        return $this->customerRepository->save($customer);
    }
}
