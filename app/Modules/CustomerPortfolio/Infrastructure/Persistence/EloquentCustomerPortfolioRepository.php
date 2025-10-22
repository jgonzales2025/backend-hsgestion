<?php

namespace App\Modules\CustomerPortfolio\Infrastructure\Persistence;

use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\CustomerPortfolio\Domain\Entities\CustomerPortfolio;
use App\Modules\CustomerPortfolio\Domain\Interfaces\CustomerPortfolioRepositoryInterface;
use App\Modules\CustomerPortfolio\Infrastructure\Models\EloquentCustomerPortfolio;

class EloquentCustomerPortfolioRepository implements CustomerPortfolioRepositoryInterface
{

    public function findAll(): array
    {
        $query = EloquentCustomerPortfolio::with('customer', 'user');

        // Obtener el usuario autenticado y su rol desde el request
        $userId = request()->get('user_id');
        $role = request()->get('role');

        // Si no es admin, filtrar solo los registros del usuario
        if ($role !== 'Administrador') {
            $query->where('user_id', $userId);
        }

        $eloquentCustomerPortfolios = $query->get();

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

        EloquentCustomer::where('id', $customerPortfolio->getCustomer()->getId())->update(['st_assigned' => 1]);

        return new CustomerPortfolio(
            id: $eloquentCustomerPortfolio->id,
            customer: $customerPortfolio->getCustomer(),
            user: $customerPortfolio->getUser(),
            created_at: $eloquentCustomerPortfolio->created_at,
            updated_at: null
        );
    }

    public function updateAllCustomersByVendedor($userId, $newId): void
    {
        EloquentCustomerPortfolio::where('user_id', $userId)->update(['user_id' => $newId]);
    }

    public function updateCustomerPortfolio($id, $userId): void
    {
        EloquentCustomerPortfolio::find($id)->update(['user_id' => $userId]);
    }
}
