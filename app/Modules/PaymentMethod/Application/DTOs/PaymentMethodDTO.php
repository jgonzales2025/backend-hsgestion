<?php

namespace App\Modules\PaymentMethod\Application\DTOs;

class PaymentMethodDTO
{
    public $description;
    public $status;

    public function __construct(array $data)
    {
        $this->description = $data['description'];
        $this->status = $data['status'];
    }
}