<?php

namespace App\Modules\CustomerPhone\Domain\Interfaces;

use App\Modules\CustomerPhone\Domain\Entities\CustomerPhone;

interface CustomerPhoneRepositoryInterface
{
    public function findAll(): array;
    public function save(CustomerPhone $customerPhone): ?CustomerPhone;
}
