<?php

namespace App\Modules\CustomerPhone\Infrastructure\Persistence;

use App\Modules\CustomerPhone\Domain\Entities\CustomerPhone;
use App\Modules\CustomerPhone\Domain\Interfaces\CustomerPhoneRepositoryInterface;
use App\Modules\CustomerPhone\Infrastructure\Models\EloquentCustomerPhone;

class EloquentCustomerPhoneRepository implements CustomerPhoneRepositoryInterface
{

    public function findAll(): array
    {
        $eloquentCustomerPhones = EloquentCustomerPhone::all()->sortByDesc('created_at');
        return $eloquentCustomerPhones->map(function ($eloquentCustomerPhone) {
            return new CustomerPhone(
                id: $eloquentCustomerPhone->id,
                phone: $eloquentCustomerPhone->phone,
                customer_id: $eloquentCustomerPhone->customer_id,
                status: $eloquentCustomerPhone->status,
            );
        })->toArray();
    }

    public function save(CustomerPhone $customerPhone): ?CustomerPhone
    {
        $eloquentCustomerPhone = EloquentCustomerPhone::create([
            'phone' => $customerPhone->getPhone(),
            'customer_id' => $customerPhone->getCustomerId(),
            'status' => $customerPhone->getStatus(),
        ]);

        return new CustomerPhone(
            id: $eloquentCustomerPhone->id,
            phone: $eloquentCustomerPhone->phone,
            customer_id: $eloquentCustomerPhone->customer_id,
            status: $eloquentCustomerPhone->status,
        );
    }
}
