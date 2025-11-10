<?php

namespace App\Modules\PurchaseOrder\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->resource->getId(),
            'serie' => $this->resource->getSerie(),
            'correlative' => $this->resource->getCorrelative(),
            'date' => $this->resource->getDate(),
            'delivery_date' => $this->resource->getDeliveryDate(),
            'contact' => $this->resource->getContact(),
            'order_number_supplier' => $this->resource->getOrderNumberSupplier(),
            'supplier' => [
                'id' => $this->resource->getSupplier()->getId(),
                'company_name' => $this->resource->getSupplier()->getCompanyName(),
            ],
            'status' => $this->resource->getStatus() == 0 ? 'Pendiente' : 'Entregado'
        ];
    }
}
