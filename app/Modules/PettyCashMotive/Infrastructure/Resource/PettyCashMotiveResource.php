<?php

namespace App\Modules\PettyCashMotive\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource; 

class PettyCashMotiveResource extends JsonResource
{

    public function toArray($pettyCashMotive): array
    {
        return [
            'id' => $this->resource->getId(),
            'description' => $this->resource->getDescription(),
            'receipt_type' => [
                'id' => $this->resource->getReceiptType()->getId(),
                'status' => $this->resource->getReceiptType()->getStatus() == 1 ? 'Activo' : 'Inactivo',
                'description' => $this->resource->getReceiptType()->getDescription(),
            ],
            'status' => ($this->resource->getStatus()) ? 'Activo' : 'Inactivo',
        ];
    }
}
