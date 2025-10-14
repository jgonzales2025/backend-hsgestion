<?php

namespace App\Modules\CustomerType\Infrastructure\Persistence;

use App\Modules\CustomerType\Domain\Entities\CustomerType;
use App\Modules\CustomerType\Domain\Interfaces\CustomerTypeRepositoryInterface;
use App\Modules\CustomerType\Infrastructure\Models\EloquentCustomerType;

class EloquentCustomerTypeRepository implements CustomerTypeRepositoryInterface
{

    public function findAll(): array
    {
        $customerTypes = EloquentCustomerType::all()->sortByDesc('created_at');

        return $customerTypes->map(function ($customerType) {
            return new CustomerType(
                id: $customerType->id,
                description: $customerType->description,
                status: $customerType->status,
            );
        })->toArray();
    }
}
