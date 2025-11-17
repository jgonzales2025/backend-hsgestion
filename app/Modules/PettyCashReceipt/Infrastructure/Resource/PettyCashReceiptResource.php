<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Resource;

use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;
use App\Modules\PettyCashMotive\Infrastructure\Models\EloquentPettyCashMotive;
use Illuminate\Http\Resources\Json\JsonResource;

class PettyCashReceiptResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'document_type' => [
                'id' => $this->resource->getDocumentType()?->getId(),
                'status' => $this->resource->getDocumentType()?->getStatus() == 1 ? 'Activo' : 'Inactivo',
                'description' => $this->resource->getDocumentType()?->getDescription(),
            ],
            'series' => $this->resource->getSeries(),
            'correlative' => $this->resource->getCorrelative(),
            'date' => $this->resource->getDate(),
            'delivered_to' => $this->resource->getDeliveredTo(),
            'reason_code' => [
                'id' => $this->resource->getReasonCode()?->getId(),
                'status' => $this->resource->getReasonCode()?->getStatus() == 1 ? 'Activo' : 'Inactivo',
                'description' => $this->resource->getReasonCode()?->getDescription(),
            ],
            'currency_type' => [
                'id' => $this->resource->getCurrencyType()?->getId(),
                'name' => $this->resource->getCurrencyType()?->getName(),
                'symbol' => $this->resource->getCurrencyType()?->getCommercialSymbol(),
            ],
            'amount' => $this->resource->getAmount(),
            'observation' => $this->resource->getObservation(),
            'status' => $this->resource->getStatus(),
            'branch' => [
                'id' => $this->resource->getBranch()->getId(),
                'name' => $this->resource->getBranch()->getName(),
                'address' => $this->resource->getBranch()->getAddress(),
            ]
        ];
    }
}
