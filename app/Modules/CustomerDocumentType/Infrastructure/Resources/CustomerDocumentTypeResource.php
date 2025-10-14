<?php

namespace App\Modules\CustomerDocumentType\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerDocumentTypeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'cod_sunat' => $this->resource->getCodSunat(),
            'description' => $this->resource->getDescription(),
            'abbreviation' => $this->resource->getAbbreviation(),
            'st_driver' => $this->resource->getStDriver(),
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
        ];
    }
}
