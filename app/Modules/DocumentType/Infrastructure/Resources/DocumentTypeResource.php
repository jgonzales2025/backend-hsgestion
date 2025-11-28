<?php

namespace App\Modules\DocumentType\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentTypeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'cod_sunat' => $this->resource->getCodSunat(),
            'description' => $this->resource->getDescription(),
            'abbreviation' => $this->resource->getAbbreviation(),
        ];

    }
}
