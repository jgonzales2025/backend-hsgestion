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
        $customers = EloquentCustomer::all()->sortByDesc('created_at');

        return $customers->map(function (EloquentCustomer $customer) {

            $contactData = $this->getCustomerContactData($customer->id);
            $phones = $contactData['phones'];
            $emails = $contactData['emails'];
            $addresses = $contactData['addresses'];

            return $this->buildCustomer($customer, $phones, $emails, $addresses);
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

        return $this->buildCustomer($eloquentCustomer, [], [], []);
    }

    public function findById(int $id): ?Customer
    {
        $eloquentCustomer = EloquentCustomer::find($id);

        if (!$eloquentCustomer) {
            return null;
        }

        $contactData = $this->getCustomerContactData($eloquentCustomer->id);
        $phones = $contactData['phones'];
        $emails = $contactData['emails'];
        $addresses = $contactData['addresses'];

        return $this->buildCustomer($eloquentCustomer, $phones, $emails, $addresses);
    }

    public function findCustomerByDocumentNumber(string $documentNumber): ?Customer
    {
        $customer = EloquentCustomer::where('document_number', $documentNumber)->first();

        if (!$customer) {
            return null;
        }

        return $this->buildCustomer($customer, [], [], []);
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

        return $this->buildCustomer($eloquentCustomer, [], [], []);
    }

    public function findAllUnassigned(): array
    {
        $customerUnassigned = EloquentCustomer::where('st_assigned', 0)->get();

        return $customerUnassigned->map(function (EloquentCustomer $customer) {

            $contactData = $this->getCustomerContactData($customer->id);
            $phones = $contactData['phones'];
            $emails = $contactData['emails'];
            $addresses = $contactData['addresses'];

            return $this->buildCustomer($customer, $phones, $emails, $addresses);
        })->toArray();
    }

    public function findCustomerCompany(): ?Customer
    {
        $companyId = request()->get('company_id');

        $customerCompany = EloquentCustomer::where('id', $companyId)->first();

        $addressUseCase = new FindByIdCustomerAddressUseCase($this->customerAddressRepository);
        $addresses = $addressUseCase->execute($customerCompany->id);

        return $this->buildCustomer($customerCompany, [], [], $addresses);
    }

    public function findAllCustomerExceptionCompanies(?string $customerName): array
    {
        $customers = EloquentCustomer::query()
            ->when($customerName, function ($query, $name) {
                return $query->where(function ($q) use ($name) {
                    $q->where('name', 'like', "%{$name}%")
                        ->orWhere('lastname', 'like', "%{$name}%")
                        ->orWhere('second_lastname', 'like', "%{$name}%")
                        ->orWhere('company_name', 'like', "%{$name}%")
                        ->orWhere('document_number', 'like', "%{$name}%");
                });
            })
            ->where('st_sales', 1)
            ->orderByDesc('created_at')
            ->get();

        return $customers->map(function (EloquentCustomer $customer) {

            $contactData = $this->getCustomerContactData($customer->id);
            $phones = $contactData['phones'];
            $emails = $contactData['emails'];
            $addresses = $contactData['addresses'];

            return $this->buildCustomer($customer, $phones, $emails, $addresses);
        })->toArray();
    }

    public function saveCustomerBySunatApi(Customer $customer): ?Customer
    {
        $eloquentCustomer = EloquentCustomer::create([
            'customer_document_type_id' => $customer->getCustomerDocumentTypeId(),
            'document_number' => $customer->getDocumentNumber(),
            'company_name' => $customer->getCompanyName(),
            'name' => $customer->getName(),
            'lastname' => $customer->getLastname(),
            'second_lastname' => $customer->getSecondLastname()
        ]);

        return $this->buildCustomer($eloquentCustomer, [], [], []);
    }

    private function buildCustomer(EloquentCustomer $customer, $phones = [], $emails = [], $addresses = []): Customer
    {
        return new Customer(
            id: $customer->id,
            record_type_id: $customer->record_type_id,
            record_type_name: $customer->recordType?->name,
            customer_document_type_id: $customer->customer_document_type_id,
            customer_document_type_name: $customer->customerDocumentType->description,
            customer_document_type_abbreviation: $customer->customerDocumentType->abbreviation,
            document_number: $customer->document_number,
            company_name: $customer->company_name,
            name: $customer->name,
            lastname: $customer->lastname,
            second_lastname: $customer->second_lastname,
            customer_type_id: $customer->customer_type_id,
            customer_type_name: $customer->customerType?->description,
            contact: $customer->contact,
            is_withholding_applicable: $customer->is_withholding_applicable,
            status: $customer->status,
            phones: $phones,
            emails: $emails,
            addresses: $addresses,
        );
    }

    private function getCustomerContactData(int $customerId): array
    {
        $phoneUseCase = new FindByCustomerIdPhoneUseCase($this->customerPhoneRepository);
        $phones = $phoneUseCase->execute($customerId);

        $emailUseCase = new FindByCustomerIdEmailUseCase($this->customerEmailRepository);
        $emails = $emailUseCase->execute($customerId);

        $addressUseCase = new FindByIdCustomerAddressUseCase($this->customerAddressRepository);
        $addresses = $addressUseCase->execute($customerId);

        return [
            'phones' => $phones,
            'emails' => $emails,
            'addresses' => $addresses,
        ];
    }
}
