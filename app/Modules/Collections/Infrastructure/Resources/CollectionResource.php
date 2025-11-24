<?php

namespace App\Modules\Collections\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'company_id' => $this->resource->getCompanyId(),
            'sale_id' => $this->resource->getSaleId(),
            'sale_document_type_id' => $this->resource->getSaleDocumentTypeId(),
            'sale_serie' => $this->resource->getSaleSerie(),
            'sale_correlative' => $this->resource->getSaleCorrelative(),
            'payment_method' => [
                'id' => $this->resource->getPaymentMethod()->getId(),
                'name' => $this->resource->getPaymentMethod()->getDescription()
            ],
            'payment_date' => $this->resource->getPaymentDate(),
            'currency_type_id' => $this->resource->getCurrencyTypeId(),
            'parallel_rate' => $this->resource->getParallelRate(),
            'amount' => $this->resource->getAmount(),
            'change' => $this->resource->getChange(),
            'digital_wallet_id' => $this->resource->getDigitalWalletId(),
            'bank_id' => $this->resource->getBankId(),
            'operation_date' => $this->resource->getOperationDate(),
            'operation_number' => $this->resource->getOperationNumber(),
            'lote_number' => $this->resource->getLoteNumber(),
            'for_digits' => $this->resource->getForDigits(),
            'status' => ($this->resource->getStatus()) == 1 ? 'Realizado' : 'Anulado',
            'credit_document_type_id' => $this->resource->getCreditDocumentTypeId(),
            'credit_serie' => $this->resource->getCreditSerie(),
            'credit_correlative' => $this->resource->getCreditCorrelative(),
        ];
    }
}
