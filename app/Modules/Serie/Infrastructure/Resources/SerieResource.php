<?php

namespace App\Modules\Serie\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SerieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'serie_number' => $this->resource->getSerieNumber(),
            'company_id' => $this->resource->getCompanyId(),
            'branch_id' => $this->resource->getBranchId(),
            'elec_document_type_id' => $this->resource->getElecDocumentTypeId(),
            'dir_document_type_id' => $this->resource->getDirDocumentTypeId(),
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
        ];
    }
}
