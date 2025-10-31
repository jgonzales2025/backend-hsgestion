<?php

namespace App\Modules\NoteReason\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteReasonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'cod_sunat' => $this->resource->getCodSunat(),
            'description' => $this->resource->getDescription(),
            'document_type_id' => $this->resource->getDocumentTypeId(),
            'stock' => $this->resource->getStock(),
            'status' => $this->resource->getStatus(),
        ];
    }
}
