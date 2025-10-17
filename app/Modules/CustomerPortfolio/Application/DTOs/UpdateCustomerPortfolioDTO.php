<?php

namespace App\Modules\CustomerPortfolio\Application\DTOs;

class UpdateCustomerPortfolioDTO
{
    public $id;
    public $newUserId;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->newUserId = $data['newUserId'];
    }
}
