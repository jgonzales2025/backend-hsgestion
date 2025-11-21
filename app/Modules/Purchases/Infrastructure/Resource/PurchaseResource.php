<?php

namespace App\Modules\Purchases\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray(Request $request):array
    {
        return [
            'id' => $this->resource->getId(),
            'branch_id' => $this->resource->getBranchId(),
            'supplier_id' => $this->resource->getSupplierId(),
            'serie' => $this->resource->getSerie(),
            'correlative' => $this->resource->getCorrelative(),
            'exchange_type' => $this->resource->getExchangeType(),
            'methodpayment' => $this->resource->getMethodpayment(),
            'currency' => $this->resource->getCurrency(),
            'date' => $this->resource->getDate(),
            'date_ven' => $this->resource->getDateVen(),
            'days' => $this->resource->getDays(),
            'observation' => $this->resource->getObservation(),
            'detraccion' => $this->resource->getDetraccion(),
            'fech_detraccion' => $this->resource->getFechDetraccion(),
            'amount_detraccion' => $this->resource->getAmountDetraccion(),
            'is_detracion' => $this->resource->getIsDetracion(),
            'subtotal' => $this->resource->getSubtotal(),
            'total_desc' => $this->resource->getTotalDesc(),
            'inafecto' => $this->resource->getInafecto(),
            'igv' => $this->resource->getIgv(),
            'total' => $this->resource->getTotal()

        ];
    }
}