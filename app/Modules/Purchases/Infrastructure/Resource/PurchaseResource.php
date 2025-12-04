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
            'branch' =>[
                'id' => $this->resource->getBranch()->getId(),
                'name' => $this->resource->getBranch()->getName(),
            ],
            'supplier' =>[
                'id' => $this->resource->getSupplier()->getId(),
                'name' => $this->resource->getSupplier()->getName() ?? $this->resource->getSupplier()-> getCompanyName(),
            ],
            'serie' => $this->resource->getSerie(),
            'correlative' => $this->resource->getCorrelative(),
            'exchange_type' => $this->resource->getExchangeType(),
            'methodpayment' => [
                'id' => $this->resource->getMethodpayment()->getId(),
                'name' => $this->resource->getMethodpayment()?->getDescription(),
            ],
            'currency' =>[
                'id' => $this->resource->getCurrency()->getId(),
                'name' => $this->resource->getCurrency()->getName(),
            ],
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
            'total' => $this->resource->getTotal(),
            'is_igv' => $this->resource->getIsIgv(),
            'reference_document_type' =>$this->resource->getTypeDocumentId(),
            'reference_serie' => $this->resource->getReferenceSerie(),
            'reference_correlative' => $this->resource->getReferenceCorrelative(),
        ];
    }
}