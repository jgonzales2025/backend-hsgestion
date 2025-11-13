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
              'document_type' => (function () {
                $code = EloquentDocumentType::where('id', $this->resource->getDocumentType())->first();

                if (!$code) {
                    return "No hay nada walter";
                }

                return (object) [
                    'id' => $code->id,
                    'status' => $code->status == 1 ? 'Activo' : 'Inactivo',
                     'description' => $code->description,
                    // 'name' => $code->address[0]['address'],
    
                ];
            })(),
            'series' => $this->resource->getSeries(),
            'correlative' => $this->resource->getCorrelative(),
            'date' => $this->resource->getDate(),
            'delivered_to' => $this->resource->getDeliveredTo(),
            'reason_code' => (function () {
                $code = EloquentPettyCashMotive::where('id', $this->resource->getReasonCode())->first();

                if (!$code) {
                    return "No hay nada walter";
                }

                return (object) [
                    'id' => $code->id,
                    'status' => $code->status == 1 ? 'Activo' : 'Inactivo',
                    'description' => $code->description,
                    // 'name' => $code->address[0]['address'],
    
                ];
            })(),
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