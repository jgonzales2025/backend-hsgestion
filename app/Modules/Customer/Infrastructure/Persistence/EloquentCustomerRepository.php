<?php

namespace App\Modules\Customer\Infrastructure\Persistence;

use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\CustomerAddress\Application\UseCases\FindByIdCustomerAddressUseCase;
use App\Modules\CustomerAddress\Domain\Interfaces\CustomerAddressRepositoryInterface;
use App\Modules\CustomerEmail\Application\UseCases\FindByCustomerIdEmailUseCase;
use App\Modules\CustomerEmail\Domain\Interfaces\CustomerEmailRepositoryInterface;
use App\Modules\CustomerPhone\Application\UseCases\FindByCustomerIdPhoneUseCase;
use App\Modules\CustomerPhone\Domain\Interfaces\CustomerPhoneRepositoryInterface;

readonly class EloquentCustomerRepository implements CustomerRepositoryInterface
{

    public function __construct(
        private readonly CustomerPhoneRepositoryInterface $customerPhoneRepository,
        private readonly CustomerEmailRepositoryInterface $customerEmailRepository,
        private readonly CustomerAddressRepositoryInterface $customerAddressRepository,
    ){}
    public function findAll(): array
    {
        $customers = EloquentCustomer::all()->sortByDesc('created_at');

        return $customers->map(function (EloquentCustomer $customer) {

            $phoneUseCase = new FindByCustomerIdPhoneUseCase($this->customerPhoneRepository);
            $phones = $phoneUseCase->execute($customer->id);

            $emailUseCase = new FindByCustomerIdEmailUseCase($this->customerEmailRepository);
            $emails = $emailUseCase->execute($customer->id);

            $addressUseCase = new FindByIdCustomerAddressUseCase($this->customerAddressRepository);;
            $addresses = $addressUseCase->execute($customer->id);

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
                customer_type_name: $customer->customerType->description,
                fax: $customer->fax,
                contact: $customer->contact,
                is_withholding_applicable: $customer->is_withholding_applicable,
                status: $customer->status,
                phones: $phones,
                emails: $emails,
                addresses: $addresses,
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
            phones: null,
            emails: null,
            addresses: null,
        );
    }
}
