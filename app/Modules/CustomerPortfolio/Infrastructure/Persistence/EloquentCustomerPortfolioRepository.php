<?php

namespace App\Modules\CustomerPortfolio\Infrastructure\Persistence;

use App\Modules\CustomerPortfolio\Domain\Entities\CustomerPortfolio;
use App\Modules\CustomerPortfolio\Domain\Interfaces\CustomerPortfolioRepositoryInterface;
use App\Modules\CustomerPortfolio\Infrastructure\Models\EloquentCustomerPortfolio;

class EloquentCustomerPortfolioRepository implements CustomerPortfolioRepositoryInterface
{

    public function findAll(): array
    {
        $eloquentCustomerPortfolios = EloquentCustomerPortfolio::with('customer', 'user')->get();

        return $eloquentCustomerPortfolios->map(function ($customerPortfolio) {
            return new CustomerPortfolio(
                id: $customerPortfolio->id,
                customer: $customerPortfolio->customer->toDomain($customerPortfolio->customer),
                user: $customerPortfolio->user->toDomain($customerPortfolio->user),
                created_at: $customerPortfolio->created_at,
                updated_at: $customerPortfolio->updated_at
            );
        })->toArray();
    }

    public function save(CustomerPortfolio $customerPortfolio): CustomerPortfolio
    {
        $eloquentCustomerPortfolio = EloquentCustomerPortfolio::create([
            'customer_id' => $customerPortfolio->getCustomer()->getId(),
            'user_id' => $customerPortfolio->getUser()->getId()
        ]);

        return new CustomerPortfolio(
            id: $eloquentCustomerPortfolio->id,
            customer: $customerPortfolio->getCustomer(),
            user: $customerPortfolio->getUser(),
            created_at: $eloquentCustomerPortfolio->created_at,
            updated_at: null
        );
    }
}
