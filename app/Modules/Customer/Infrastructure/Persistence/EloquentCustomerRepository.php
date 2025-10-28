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
    public function findAll(?string $customerName, ?string $documentNumber): array
    {
        $customers = EloquentCustomer::query()
            ->when($customerName, function ($query, $name) {
                return $query->where(function ($q) use ($name) {
                    $q->where('name', 'like', "%{$name}%")
                        ->orWhere('company_name', 'like', "%{$name}%")
                        ->orWhere('document_number', 'like', "%{$name}%");
                });
            })
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->get();

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
            'contact' => $customer->getContact(),
            'is_withholding_applicable' => $customer->isWithholdingApplicable(),
            'status' => $customer->getStatus(),
            'st_assigned' => $customer->getStAssigned()
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
            contact: $eloquentCustomer->contact,
            is_withholding_applicable: $eloquentCustomer->is_withholding_applicable,
            status: $eloquentCustomer->status
        );
    }

    public function findById(int $id): ?Customer
    {
        $eloquentCustomer = EloquentCustomer::find($id);

        if (!$eloquentCustomer) {
            return null;
        }

        // Cargar teléfonos
        $phoneUseCase = new FindByCustomerIdPhoneUseCase($this->customerPhoneRepository);
        $phones = $phoneUseCase->execute($eloquentCustomer->id);

        // Cargar emails
        $emailUseCase = new FindByCustomerIdEmailUseCase($this->customerEmailRepository);
        $emails = $emailUseCase->execute($eloquentCustomer->id);

        // Cargar direcciones - AGREGAR ESTO
        $addressUseCase = new FindByIdCustomerAddressUseCase($this->customerAddressRepository);
        $addresses = $addressUseCase->execute($eloquentCustomer->id);

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
            contact: $eloquentCustomer->contact,
            is_withholding_applicable: $eloquentCustomer->is_withholding_applicable,
            status: $eloquentCustomer->status,
            phones: $phones,
            emails: $emails,
            addresses: $addresses
        );
    }

    public function update(Customer $customer): ?Customer
    {
        $eloquentCustomer = EloquentCustomer::find($customer->getId());

        if (!$eloquentCustomer) {
            return null;
        }

        $eloquentCustomer->update([
            'record_type_id' => $customer->getRecordTypeId(),
            'customer_document_type_id' => $customer->getCustomerDocumentTypeId(),
            'document_number' => $customer->getDocumentNumber(),
            'company_name' => $customer->getCompanyName(),
            'name' => $customer->getName(),
            'lastname' => $customer->getLastname(),
            'second_lastname' => $customer->getSecondLastname(),
            'customer_type_id' => $customer->getCustomerTypeId(),
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
            contact: $eloquentCustomer->contact,
            is_withholding_applicable: $eloquentCustomer->is_withholding_applicable,
            status: $eloquentCustomer->status
        );
    }

    public function findAllUnassigned(): array
    {
        $customerUnassigned = EloquentCustomer::where('st_assigned', 0)->get();

        return $customerUnassigned->map(function (EloquentCustomer $customer) {

            // Cargar teléfonos
            $phoneUseCase = new FindByCustomerIdPhoneUseCase($this->customerPhoneRepository);
            $phones = $phoneUseCase->execute($customer->id);

            // Cargar emails
            $emailUseCase = new FindByCustomerIdEmailUseCase($this->customerEmailRepository);
            $emails = $emailUseCase->execute($customer->id);

            // Cargar direcciones - AGREGAR ESTO
            $addressUseCase = new FindByIdCustomerAddressUseCase($this->customerAddressRepository);
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
                contact: $customer->contact,
                is_withholding_applicable: $customer->is_withholding_applicable,
                status: $customer->status,
                phones: $phones,
                emails: $emails,
                addresses: $addresses,
            );
        })->toArray();
    }

    public function findCustomerCompany(): ?Customer
    {
        $companyId = request()->get('company_id');

        $customerCompany = EloquentCustomer::where('id', $companyId)->first();

        $addressUseCase = new FindByIdCustomerAddressUseCase($this->customerAddressRepository);
        $addresses = $addressUseCase->execute($customerCompany->id);

        return new Customer(
            id: $customerCompany->id,
            record_type_id: $customerCompany->record_type_id,
            record_type_name: $customerCompany->recordType->name,
            customer_document_type_id: $customerCompany->customer_document_type_id,
            customer_document_type_name: $customerCompany->customerDocumentType->description,
            customer_document_type_abbreviation: $customerCompany->customerDocumentType->abbreviation,
            document_number: $customerCompany->document_number,
            company_name: $customerCompany->company_name,
            name: $customerCompany->name,
            lastname: $customerCompany->lastname,
            second_lastname: $customerCompany->second_lastname,
            customer_type_id: $customerCompany->customer_type_id,
            customer_type_name: $customerCompany->customerType->description,
            contact: $customerCompany->contact,
            is_withholding_applicable: $customerCompany->is_withholding_applicable,
            status: $customerCompany->status,
            addresses: $addresses
        );
    }
}
