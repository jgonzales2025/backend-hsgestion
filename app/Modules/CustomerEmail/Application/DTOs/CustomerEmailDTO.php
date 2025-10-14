<?php

namespace App\Modules\CustomerEmail\Application\DTOs;

class CustomerEmailDTO
{
    public $email;
    public $customer_id;
    public $status;

    public function __construct(array $data)
    {
        $this->email = $data['email'];
        $this->customer_id = $data['customer_id'];
        $this->status = $data['status'];
    }
}
