<?php

namespace App\Modules\Sale\Infrastructure\Persistence;

use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;

class EloquentSaleRepository implements SaleRepositoryInterface
{

    public function save(Sale $sale): ?Sale
    {
        // TODO: Implement save() method.
    }
}
