<?php

namespace App\Modules\CustomerPhone\Application\DTOs;

class CustomerPhoneDTO
{
    public $phone;
    public $customer_id;
    public $status;

    public function __construct(array $data)
    {
        $this->phone = $data['phone'];
        $this->customer_id = $data['customer_id'];
        $this->status = $data['status'] ?? null;
    }
}
