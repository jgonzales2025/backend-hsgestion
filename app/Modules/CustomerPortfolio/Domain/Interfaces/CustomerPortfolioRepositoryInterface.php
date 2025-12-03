<?php

namespace App\Modules\CustomerPortfolio\Domain\Interfaces;

use App\Modules\CustomerPortfolio\Domain\Entities\CustomerPortfolio;
use App\Modules\User\Domain\Entities\User;

interface CustomerPortfolioRepositoryInterface
{
    public function findAll(?string $description);

    public function save(CustomerPortfolio $customerPortfolio): CustomerPortfolio;

    public function updateAllCustomersByVendedor($id, $newId): void;

    public function updateCustomerPortfolio($id, $userId): void;

    public function findUserByCustomerId($customerId): null|User|array;
}
