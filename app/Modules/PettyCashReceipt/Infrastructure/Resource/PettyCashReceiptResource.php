<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class PettyCashReceiptResource extends JsonResource{
        public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            // 'company' => $this->resource->getCompany(),
            'document_type' => $this->resource->getDocumentType(),
            'series' => $this->resource->getSeries(),
            'correlative' => $this->resource->getCorrelative(),
            'date' => $this->resource->getDate(),
            'delivered_to' => $this->resource->getDeliveredTo(),
            'reason_code' => $this->resource->getReasonCode(),
            'currency_type' => $this->resource->getCurrencyType(),
            'amount' => $this->resource->getAmount(),
            'observation' => $this->resource->getObservation(),
            'status' => $this->resource->getStatus(),
            'created_by' => $this->resource->getCreatedBy(),
            'created_at_manual' => $this->resource->getCreatedAtManual(),
            'updated_by' => $this->resource->getUpdatedBy(),
            'updated_at_manual' => $this->resource->getUpdatedAtManual(),
            
        ];
    }
}