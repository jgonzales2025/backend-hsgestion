<?php

namespace App\Modules\CustomerEmail\Infrastructure\Persistence;

use App\Modules\CustomerEmail\Domain\Entities\CustomerEmail;
use App\Modules\CustomerEmail\Domain\Interfaces\CustomerEmailRepositoryInterface;
use App\Modules\CustomerEmail\Infrastructure\Models\EloquentCustomerEmail;

class EloquentCustomerEmailRepository implements CustomerEmailRepositoryInterface
{

    public function save(CustomerEmail $customerEmail): ?CustomerEmail
    {
        $eloquentCustomerEmail = EloquentCustomerEmail::create([
            'email' => $customerEmail->getEmail(),
            'customer_id' => $customerEmail->getCustomerId(),
            'status' => $customerEmail->getStatus(),
        ]);

        return new CustomerEmail(
            id: $eloquentCustomerEmail->id,
            email: $eloquentCustomerEmail->email,
            customer_id: $eloquentCustomerEmail->customer_id,
            status: $eloquentCustomerEmail->status,
        );
    }
}
