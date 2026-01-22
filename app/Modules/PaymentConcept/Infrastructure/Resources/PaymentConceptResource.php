<?php

namespace App\Modules\PaymentConcept\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentConceptResource extends JsonResource
{
    public function toArray(Request $request)
    {
        $isEntity = method_exists($this->resource, 'getId');

        if ($isEntity) {
            return [
                'id' => $this->resource->getId(),
                'description' => $this->resource->getDescription(),
                'status' => $this->resource->getStatus() == 1 ? 'Activo' : 'Inactivo',
            ];
        } else {
            return [
                'id' => $this->resource->id,
                'description' => $this->resource->description,
                'status' => $this->resource->status == 1 ? 'Activo' : 'Inactivo',
            ];
        }
    }
}
