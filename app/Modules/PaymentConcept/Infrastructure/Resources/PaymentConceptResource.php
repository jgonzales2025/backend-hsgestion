<?php

namespace App\Modules\PaymentConcept\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentConceptResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->resource->getId(),
            'description' => $this->resource->getDescription(),
            'status' => $this->resource->getStatus() == 1 ? 'Activo' : 'Inactivo',
        ];
    }
}