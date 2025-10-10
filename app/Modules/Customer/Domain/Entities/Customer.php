<?php

namespace App\Modules\Customer\Domain\Entities;

class Customer
{
    private int $id;
    private int $record_type_id;
    private int $customer_document_type_id;
    private string $document_number;
    private ?string $company_name;
    private ?string $name;
    private ?string $lastname;
    private ?string $second_lastname;
    private int $customer_type_id;
    private ?string $customer_type_name;
    private ?string $fax;
    private ?string $contact;
    private bool $is_withholding_applicable;
    private int $status;

    public function __construct(
        int $id,
        int $record_type_id,
        int $customer_document_type_id,
        string $document_number,
        ?string $company_name,
        ?string $name,
        ?string $lastname,
        ?string $second_lastname,
        int $customer_type_id,
        ?string $customer_type_name,
        ?string $fax,
        ?string $contact,
        bool $is_withholding_applicable,
        int $status
    ) {
        $this->id = $id;
        $this->record_type_id = $record_type_id;
        $this->customer_document_type_id = $customer_document_type_id;
        $this->document_number = $document_number;
        $this->company_name = $company_name;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->second_lastname = $second_lastname;
        $this->customer_type_id = $customer_type_id;
        $this->customer_type_name = $customer_type_name;
        $this->fax = $fax;
        $this->contact = $contact;
        $this->is_withholding_applicable = $is_withholding_applicable;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getRecordTypeId(): int { return $this->record_type_id; }
    public function getCustomerDocumentTypeId(): int { return $this->customer_document_type_id; }
    public function getDocumentNumber(): string { return $this->document_number; }
    public function getCompanyName(): ?string { return $this->company_name; }
    public function getName(): ?string { return $this->name; }
    public function getLastname(): ?string { return $this->lastname; }
    public function getSecondLastname(): ?string { return $this->second_lastname; }
    public function getCustomerTypeId(): int { return $this->customer_type_id; }
    public function getCustomerTypeName(): string|null { return $this->customer_type_name; }
    public function getFax(): ?string { return $this->fax; }
    public function getContact(): ?string { return $this->contact; }
    public function isWithholdingApplicable(): bool { return $this->is_withholding_applicable; }
    public function getStatus(): int { return $this->status; }
}
