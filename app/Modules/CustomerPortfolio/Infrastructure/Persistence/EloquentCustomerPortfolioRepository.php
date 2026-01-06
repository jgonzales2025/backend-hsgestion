<?php

namespace App\Modules\CustomerPortfolio\Infrastructure\Persistence;

use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\CustomerPortfolio\Domain\Entities\CustomerPortfolio;
use App\Modules\CustomerPortfolio\Domain\Interfaces\CustomerPortfolioRepositoryInterface;
use App\Modules\CustomerPortfolio\Infrastructure\Models\EloquentCustomerPortfolio;
use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Infrastructure\Model\EloquentUser;

class EloquentCustomerPortfolioRepository implements CustomerPortfolioRepositoryInterface
{

    public function findAll(?string $description)
    {
        $query = EloquentCustomerPortfolio::with('customer', 'user')
        ->when($description, fn($query) => $query->whereHas('customer', fn($query) => $query->where('name', 'like', "%{$description}%")))
            ->orWhereHas('customer', fn($query) => $query->where('document_number', 'like', "%{$description}%"))
            ->orWhereHas('customer', fn($query) => $query->where('company_name', 'like', "%{$description}%"))
            ->orWhereHas('customer', fn($query) => $query->where('lastname', 'like', "%{$description}%"))
            ->orWhereHas('customer', fn($query) => $query->where('second_lastname', 'like', "%{$description}%"))
            ->orWhereHas('user', fn($query) => $query->where('username', 'like', "%{$description}%"))
            ->orWhereHas('user', fn($query) => $query->where('firstname', 'like', "%{$description}%"))
            ->orWhereHas('user', fn($query) => $query->where('lastname', 'like', "%{$description}%"))
        ->orderBy('created_at', 'desc');

        // Obtener el usuario autenticado y su rol desde el request
        $userId = request()->get('user_id');
        $role = request()->get('role');

        // Si no es admin, filtrar solo los registros del usuario
        if ($role !== 'Gerencia') {
            $query->where('user_id', $userId);
        }

        $eloquentCustomerPortfolios = $query->paginate(10);

        $eloquentCustomerPortfolios->getCollection()->transform(fn($customerPortfolio) => new CustomerPortfolio(
                id: $customerPortfolio->id,
                customer: $customerPortfolio->customer->toDomain($customerPortfolio->customer),
                user: $customerPortfolio->user->toDomain($customerPortfolio->user),
                created_at: $customerPortfolio->created_at,
                updated_at: $customerPortfolio->updated_at
            ));

        return $eloquentCustomerPortfolios;
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

    public function findUserByCustomerId($customerId): null|User|array
    {
        $role = request()->get('role');

        if ($role == 'Ventas')
        {
            $eloquentCustomerPortfolio = EloquentCustomerPortfolio::where('customer_id', $customerId)->first();

            if (!$eloquentCustomerPortfolio) {
                return null;
            }

            return new User(
                id: $eloquentCustomerPortfolio->user->id,
                username: $eloquentCustomerPortfolio->user->username,
                firstname: $eloquentCustomerPortfolio->user->firstname,
                lastname: $eloquentCustomerPortfolio->user->lastname,
                password: null,
                status: $eloquentCustomerPortfolio->user->status,
                roles: null,
                assignment: null,
                st_login: null
            );
        } else {
            $eloquentUsers = EloquentUser::all();

            return $eloquentUsers->map(function ($user) {
                return new User(
                    id: $user->id,
                    username: $user->username,
                    firstname: $user->firstname,
                    lastname: $user->lastname,
                    password: null,
                    status: $user->status,
                    roles: null,
                    assignment: null,
                    st_login: null
                );
            })->toArray();
        }

    }
}
