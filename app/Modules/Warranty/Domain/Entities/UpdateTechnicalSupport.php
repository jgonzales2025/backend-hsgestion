<?php

namespace App\Modules\Warranty\Domain\Entities;

class UpdateTechnicalSupport
{
    public ?string $customer_phone;
    public ?string $customer_email;
    public ?string $failure_description;
    public ?string $observations;
    public ?string $diagnosis;
    public ?string $contact;
    
    public function __construct(
        ?string $customer_phone,
        ?string $customer_email,
        ?string $failure_description,
        ?string $observations,
        ?string $diagnosis,
        ?string $contact
    ) {
        $this->customer_phone = $customer_phone;
        $this->customer_email = $customer_email;
        $this->failure_description = $failure_description;
        $this->observations = $observations;
        $this->diagnosis = $diagnosis;
        $this->contact = $contact;
    }
    
    public function getCustomerPhone(): ?string { return $this->customer_phone; }
    
    public function getCustomerEmail(): ?string { return $this->customer_email; }
    
    public function getFailureDescription(): ?string { return $this->failure_description; }
    
    public function getObservations(): ?string { return $this->observations; }
    
    public function getDiagnosis(): ?string { return $this->diagnosis; }
    
    public function getContact(): ?string { return $this->contact; }
}