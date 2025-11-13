<?php

namespace App\Modules\PettyCashMotive\Infrastructure\Resource;

use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;
use Illuminate\Http\Resources\Json\JsonResource;

class PettyCashMotiveResource extends JsonResource
{

    public function toArray($pettyCashMotive): array
    {
        return [
            'id' => $this->resource->getId(),
            'description' => $this->resource->getDescription(),
            'receipt_type' => (function () {
                $code = EloquentDocumentType::where('st_invoices', true)
                    ->where('id', $this->resource->getReceiptType())
                    ->first();

                if (!$code) {
                    return "No hay nada, Walter ";
                }

                return (object) [
                    'id' => $code->id,
                    'status' => $code->status == 1 ? 'Activo' : 'Inactivo',
                    'description' => $code->description,
                ];
            })(),
            'status' => ($this->resource->getStatus()) ? 'Activo' : 'Inactivo',
        ];
    }
}