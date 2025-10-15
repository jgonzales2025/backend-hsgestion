<?php

namespace App\Modules\Bank\Application\DTOs;

class BankDTO
{
    public $name;
    public $account_number;
    public $currency_type_id;
    public $user_id;
    public $company_id;
    public $status;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->account_number = $data['account_number'];
        $this->currency_type_id = $data['currency_type_id'];
        $this->user_id = $data['user_id'];
        $this->company_id = $data['company_id'];
        $this->status = $data['status'];
    }
}
