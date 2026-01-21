<?php

namespace App\Modules\Warranty\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\WarrantyStatus\Domain\Entities\WarrantyStatus;

class TechnicalSupport
{
    public int $id;
    public int $document_type_warranty_id;
    public Company $company;
    public Branch $branch;
    public string $serie;
    public string $correlative;
    public string $date;
    public string $customer_phone;
    public string $customer_email;
    public string $failure_description;
    public ?string $observations;
    public string $diagnosis;
    public ?string $contact;
    public ?WarrantyStatus $warranty_status;

    public function __construct(
        int $id,
        int $document_type_warranty_id,
        Company $company,
        Branch $branch,
        string $serie,
        string $correlative,
        string $date,
        string $customer_phone,
        string $customer_email,
        string $failure_description,
        ?string $observations,
        string $diagnosis,
        ?string $contact,
        ?WarrantyStatus $warranty_status = null
    ) {
        $this->id = $id;
        $this->document_type_warranty_id = $document_type_warranty_id;
        $this->company = $company;
        $this->branch = $branch;
        $this->serie = $serie;
        $this->correlative = $correlative;
        $this->date = $date;
        $this->customer_phone = $customer_phone;
        $this->customer_email = $customer_email;
        $this->failure_description = $failure_description;
        $this->observations = $observations;
        $this->diagnosis = $diagnosis;
        $this->contact = $contact;
        $this->warranty_status = $warranty_status;
    }

    public function getId(): int { return $this->id; }
    public function getDocumentTypeWarrantyId(): int { return $this->document_type_warranty_id; }
    public function getCompany(): Company { return $this->company; }
    public function getBranch(): Branch { return $this->branch; }
    public function getSerie(): string { return $this->serie; }
    public function getCorrelative(): string { return $this->correlative; }
    public function getDate(): string { return $this->date; }
    public function getCustomerPhone(): string { return $this->customer_phone; }
    public function getCustomerEmail(): string { return $this->customer_email; }
    public function getFailureDescription(): string { return $this->failure_description; }
    public function getObservations(): ?string { return $this->observations; }
    public function getDiagnosis(): string { return $this->diagnosis; }
    public function getContact(): ?string { return $this->contact; }
    public function getWarrantyStatus(): ?WarrantyStatus { return $this->warranty_status; }

}
