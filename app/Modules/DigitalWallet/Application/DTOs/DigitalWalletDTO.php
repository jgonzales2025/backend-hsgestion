<?php

namespace App\Modules\DigitalWallet\Application\DTOs;

class DigitalWalletDTO
{
    public $name;
    public $phone;
    public $company_id;
    public $user_id;
    public $status;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->phone = $data['phone'];
        $this->company_id = $data['company_id'];
        $this->user_id = $data['user_id'];
        $this->status = $data['status'];
    }
}
