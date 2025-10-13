<?php

namespace App\Modules\CustomerAddress\Domain\Interfaces;

use App\Modules\CustomerAddress\Domain\Entities\CustomerAddress;

interface CustomerAddressRepositoryInterface
{
    public function save(CustomerAddress $customerAddress): ?CustomerAddress;

    public function findByCustomerId(int $customerId): array;
}
