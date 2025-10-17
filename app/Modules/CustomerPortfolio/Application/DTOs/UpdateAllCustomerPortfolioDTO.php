<?php

namespace App\Modules\CustomerPortfolio\Application\DTOs;

class UpdateAllCustomerPortfolioDTO
{
    public $userId;
    public $newUserId;

    public function __construct(array $data)
    {
        $this->userId = $data['id'];
        $this->newUserId = $data['newUserId'];
    }
}
