<?php

namespace App\Modules\Detraction\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetractionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->getId(),
            'cod_sunat' => $this->resource->getCodSunat(),
            'description' => $this->resource->getDescription(),
            'percentage' => $this->resource->getPercentage(),
        ];
    }
}