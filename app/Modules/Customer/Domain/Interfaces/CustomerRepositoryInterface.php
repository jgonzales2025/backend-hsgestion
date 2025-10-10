<?php

namespace App\Modules\Customer\Domain\Interfaces;

use App\Modules\Customer\Domain\Entities\Customer;

interface CustomerRepositoryInterface
{
    public function findAll(): array;
    public function save(Customer $customer): ?Customer;
}
