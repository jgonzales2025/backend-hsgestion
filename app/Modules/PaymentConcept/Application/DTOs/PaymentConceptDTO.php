<?php

namespace App\Modules\PaymentConcept\Application\DTOs;

class PaymentConceptDTO
{
    public string $description;

    public function __construct(array $data)
    {
        $this->description = $data['description'];
    }
}