<?php

namespace App\Modules\Driver\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'customer_document_type_id' => $this->resource->getCustomerDocumentTypeId(),
            'document_type_name' => $this->resource->getDocumentTypeName(),
            'doc_number' => $this->resource->getDocNumber(),
            'name' => $this->resource->getName(),
            'pat_surname' => $this->resource->getPatSurname(),
            'mat_surname' => $this->resource->getMatSurname(),
            'license' => $this->resource->getLicense(),
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
        ];
    }
}
