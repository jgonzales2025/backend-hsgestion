<?php

namespace App\Modules\Customer\Domain\Entities;

class Customer
{
    private int $id;
    private ?int $record_type_id;
    private ?string $record_type_name;
    private int $customer_document_type_id;
    private ?string $customer_document_type_name;
    private ?string $customer_document_type_abbreviation;
    private string $document_number;
    private ?string $company_name;
    private ?string $name;
    private ?string $lastname;
    private ?string $second_lastname;
    private ?int $customer_type_id;
    private ?string $customer_type_name;
    private ?string $contact;
    private ?bool $is_withholding_applicable;
    private ?int $status;
    private int $st_assigned;
    private ?array $phones;
    private ?array $emails;
    private ?array $addresses;

    public function __construct(
        int $id,
        ?int $record_type_id,
        ?string $record_type_name,
        int $customer_document_type_id,
        ?string $customer_document_type_name,
        ?string $customer_document_type_abbreviation,
        string $document_number,
        ?string $company_name,
        ?string $name,
        ?string $lastname,
        ?string $second_lastname,
        ?int $customer_type_id,
        ?string $customer_type_name,
        ?string $contact,
        ?bool $is_withholding_applicable,
        ?int $status,
        int $st_assigned = 0,
        ?array $phones = null,
        ?array $emails = null,
        ?array $addresses = null,
    ) {
        $this->id = $id;
        $this->record_type_id = $record_type_id;
        $this->record_type_name = $record_type_name;
        $this->customer_document_type_id = $customer_document_type_id;
        $this->customer_document_type_name = $customer_document_type_name;
        $this->customer_document_type_abbreviation = $customer_document_type_abbreviation;
        $this->document_number = $document_number;
        $this->company_name = $company_name;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->second_lastname = $second_lastname;
        $this->customer_type_id = $customer_type_id;
        $this->customer_type_name = $customer_type_name;
        $this->contact = $contact;
        $this->is_withholding_applicable = $is_withholding_applicable;
        $this->status = $status;
        $this->st_assigned = $st_assigned;
        $this->phones = $phones;
        $this->emails = $emails;
        $this->addresses = $addresses;
    }

    public function getId(): int { return $this->id; }
    public function getRecordTypeId(): int|null { return $this->record_type_id; }
    public function getRecordTypeName(): string|null { return $this->record_type_name; }
    public function getCustomerDocumentTypeId(): int { return $this->customer_document_type_id; }
    public function getCustomerDocumentTypeName(): string|null { return $this->customer_document_type_name; }
    public function getCustomerDocumentTypeAbbreviation(): string|null { return $this->customer_document_type_abbreviation; }
    public function getDocumentNumber(): string { return $this->document_number; }
    public function getCompanyName(): ?string { return $this->company_name; }
    public function getName(): ?string { return $this->name; }
    public function getLastname(): ?string { return $this->lastname; }
    public function getSecondLastname(): ?string { return $this->second_lastname; }
    public function getCustomerTypeId(): int|null { return $this->customer_type_id; }
    public function getCustomerTypeName(): string|null { return $this->customer_type_name; }
    public function getContact(): ?string { return $this->contact; }
    public function isWithholdingApplicable(): bool|null { return $this->is_withholding_applicable; }
    public function getStatus(): int|null { return $this->status; }
    public function getStAssigned(): int { return $this->st_assigned; }
    public function getPhones(): ?array { return $this->phones; }
    public function getEmails(): ?array { return $this->emails; }
    public function getAddresses(): ?array { return $this->addresses; }
}
