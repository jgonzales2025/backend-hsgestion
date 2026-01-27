<?php

namespace App\Modules\Warranty\Application\DTOs;

class TechnicalSupportDTO
{
    public int $document_type_warranty_id;
    public int $company_id;
    public int $branch_id;
    public string $serie;
    public string $correlative;
    public string $date;
    public string $customer_phone;
    public ?string $customer_email;
    public string $failure_description;
    public ?string $observations;
    public string $diagnosis;
    public ?string $contact;

    public function __construct(array $data) {
        $this->document_type_warranty_id = $data['document_type_warranty_id'];
        $this->company_id = $data['company_id'];
        $this->branch_id = $data['branch_id'];
        $this->serie = $data['serie'];
        $this->date = $data['date'];
        $this->customer_phone = $data['customer_phone'];
        $this->customer_email = $data['customer_email'] ?? null;
        $this->failure_description = $data['failure_description'];
        $this->observations = $data['observations'] ?? null;
        $this->diagnosis = $data['diagnosis'];
        $this->contact = $data['contact'] ?? null;
    }
}