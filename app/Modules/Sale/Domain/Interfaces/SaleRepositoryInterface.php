<?php

namespace App\Modules\Sale\Domain\Interfaces;

use App\Modules\Sale\Domain\Entities\Sale;

interface SaleRepositoryInterface
{
    public function save(Sale $sale): ?Sale;
}
