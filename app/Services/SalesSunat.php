<?php

namespace App\Services;

use App\Modules\Sale\Infrastructure\Models\EloquentSale;

class SalesSunat
{
    public function saleGravada(int $saleId)
    {
        $sale = EloquentSale::find($saleId);
    }
}