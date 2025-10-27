<?php

namespace App\Modules\MonthlyClosure\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlyClosureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'year' => $this->resource->getYear(),
            'month' => $this->resource->getMonth(),
            'st_purchases' => ($this->resource->getStPurchases()) == 1 ? 'Abierto' : 'Cerrado',
            'st_sales' => ($this->resource->getStSales()) == 1 ? 'Abierto' : 'Cerrado',
            'st_cash' => ($this->resource->getStCash()) == 1 ? 'Abierto' : 'Cerrado',
        ];
    }
}
