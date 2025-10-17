<?php

namespace App\Modules\CustomerPortfolio\Application\DTOs;

use App\Modules\CustomerPortfolio\Domain\Interfaces\CustomerPortfolioRepositoryInterface;

class UpdateCustomerPortfolioDTO
{
    public $userId;

    public function __construct(array $data)
    {
        $this->userId = $data['user_id'];
    }
}
