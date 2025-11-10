<?php

namespace App\Modules\EntryGuides\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



class EntryGuideResource extends JsonResource{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'serie' => $this->resource->getSerie(), 
            'correlativo' => $this->resource->getCorrelativo(),

            'date' => $this->resource->getDate(),
            'observations' => $this->resource->getObservations(),

            'reference_serie' => $this->resource->getReferenceSerie(),
            'reference_correlative' => $this->resource->getReferenceCorrelative(),

            'status' => $this->resource->getStatus(),
            
        ];
    }
}