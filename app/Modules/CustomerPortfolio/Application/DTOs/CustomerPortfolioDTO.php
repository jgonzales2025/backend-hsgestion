<?php

namespace App\Modules\CustomerPortfolio\Application\DTOs;

class CustomerPortfolioDTO
{
    public array $customer_ids;
    public $user_id;

    public function __construct(array $data)
    {
        $this->customer_ids = $data['customer_ids'];
        $this->user_id = $data['user_id'];
    }
}
