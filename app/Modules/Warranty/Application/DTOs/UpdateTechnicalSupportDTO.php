<?php

namespace App\Modules\Warranty\Application\DTOs;

class UpdateTechnicalSupportDTO
{
    public ?string $customer_phone;
    public ?string $customer_email;
    public ?string $failure_description;
    public ?string $observations;
    public ?string $diagnosis;
    public ?string $contact;
    
    public function __construct(array $data)
    {
        $this->customer_phone = $data['customer_phone'] ?? null;
        $this->customer_email = $data['customer_email'] ?? null;
        $this->failure_description = $data['failure_description'] ?? null;
        $this->observations = $data['observations'] ?? null;
        $this->diagnosis = $data['diagnosis'] ?? null;
        $this->contact = $data['contact'] ?? null;
    }
}
