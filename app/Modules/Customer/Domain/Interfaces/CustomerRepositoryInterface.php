<?php

namespace App\Modules\Customer\Domain\Interfaces;

use App\Modules\Customer\Domain\Entities\Customer;

interface CustomerRepositoryInterface
{
    public function findAll(?string $customerName, ?string $documentNumber): array;
    public function save(Customer $customer): ?Customer;
    public function findById(int $id): ?Customer;
    public function update(Customer $customer): ?Customer;
    public function findAllUnassigned(): array;
    public function findCustomerCompany(): ?Customer;
    public function findAllCustomerExceptionCompanies(?string $customerName, ?int $status, ?int $documentTypeId);
    public function saveCustomerBySunatApi(Customer $customer, string $document): ?Customer;
    public function findCustomerByDocumentNumber(string $documentNumber): ?Customer;
    public function findAllCustomersSuppliers(): array;
    public function updateStatus(int $customerId, int $status): void;
}
