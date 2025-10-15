<?php

namespace App\Modules\ExchangeRate\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeRateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'date' => $this->resource->getDate(),
            'purchase_rate' => $this->resource->getPurchaseRate(),
            'sale_rate' => $this->resource->getSaleRate(),
            'parallel_rate' => $this->resource->getParallelRate(),
        ];
    }
}
