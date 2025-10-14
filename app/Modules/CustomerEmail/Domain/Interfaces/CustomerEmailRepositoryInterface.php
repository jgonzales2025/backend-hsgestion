<?php

namespace App\Modules\CustomerEmail\Domain\Interfaces;

use App\Modules\CustomerEmail\Domain\Entities\CustomerEmail;

interface CustomerEmailRepositoryInterface
{
    public function save(CustomerEmail $customerEmail) :?CustomerEmail;

    public function findByCustomerId(int $customerId): array;

}
