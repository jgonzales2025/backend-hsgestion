<?php

namespace App\Modules\Customer\Infrastructure\Persistence;

use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;

class EloquentCustomerRepository implements CustomerRepositoryInterface
{

    public function findAll(): array
    {
        $customers = EloquentCustomer::all()->sortByDesc('created_at');

        return $customers->map(function (EloquentCustomer $customer) {
            return new Customer(
                id: $customer->id,
                record_type_id: $customer->record_type_id,
                record_type_name: $customer->recordType->name,
                customer_document_type_id: $customer->customer_document_type_id,
                customer_document_type_name: $customer->customerDocumentType->description,
                customer_document_type_abbreviation: $customer->customerDocumentType->abbreviation,
                document_number: $customer->document_number,
                company_name: $customer->company_name,
                name: $customer->name,
                lastname: $customer->lastname,
                second_lastname: $customer->second_lastname,
                customer_type_id: $customer->customer_type_id,
                customer_type_name: $customer->customerType->name,
                fax: $customer->fax,
                contact: $customer->contact,
                is_withholding_applicable: $customer->is_withholding_applicable,
                status: $customer->status
            );
        })->toArray();
    }

    public function save(Customer $customer): ?Customer
    {
        $eloquentCustomer = EloquentCustomer::create([
            'record_type_id' => $customer->getRecordTypeId(),
            'customer_document_type_id' => $customer->getCustomerDocumentTypeId(),
            'document_number' => $customer->getDocumentNumber(),
            'company_name' => $customer->getCompanyName(),
            'name' => $customer->getName(),
            'lastname' => $customer->getLastname(),
            'second_lastname' => $customer->getSecondLastname(),
            'customer_type_id' => $customer->getCustomerTypeId(),
            'fax' => $customer->getFax(),
            'contact' => $customer->getContact(),
            'is_withholding_applicable' => $customer->isWithholdingApplicable(),
            'status' => $customer->getStatus(),
        ]);

        return new Customer(
            id: $eloquentCustomer->id,
            record_type_id: $eloquentCustomer->record_type_id,
            record_type_name: $eloquentCustomer->recordType->name,
            customer_document_type_id: $eloquentCustomer->customer_document_type_id,
            customer_document_type_name: $eloquentCustomer->customerDocumentType->description,
            customer_document_type_abbreviation: $eloquentCustomer->customerDocumentType->abbreviation,
            document_number: $eloquentCustomer->document_number,
            company_name: $eloquentCustomer->company_name,
            name: $eloquentCustomer->name,
            lastname: $eloquentCustomer->lastname,
            second_lastname: $eloquentCustomer->second_lastname,
            customer_type_id: $eloquentCustomer->customer_type_id,
            customer_type_name: $eloquentCustomer->customerType->description,
            fax: $eloquentCustomer->fax,
            contact: $eloquentCustomer->contact,
            is_withholding_applicable: $eloquentCustomer->is_withholding_applicable,
            status: $eloquentCustomer->status,
        );
    }
}
