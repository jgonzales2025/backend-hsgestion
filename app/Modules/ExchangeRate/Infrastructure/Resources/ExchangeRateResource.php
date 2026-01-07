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
            'almacen' => $this->resource->getAlmacen() == 0 ? 0 : 1,
            'compras' => $this->resource->getCompras() == 0 ? 0 : 1,
            'ventas' => $this->resource->getVentas() == 0 ? 0 : 1,
            'cobranzas' => $this->resource->getCobranzas() == 0 ? 0 : 1,
            'pagos' => $this->resource->getPagos() == 0 ? 0 : 1,
        ];
    }
}
