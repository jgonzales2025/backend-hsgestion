<?php

namespace App\Modules\CustomerPortfolio\Domain\Interfaces;

use App\Modules\CustomerPortfolio\Domain\Entities\CustomerPortfolio;

interface CustomerPortfolioRepositoryInterface
{
    public function findAll(): array;

    public function save(CustomerPortfolio $customerPortfolio): CustomerPortfolio;

    public function update($id, $newId): void;
}
