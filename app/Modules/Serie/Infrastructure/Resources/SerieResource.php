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
            'company' => [
                'id' => $this->resource->getCompany()->getId(),
                'ruc' => $this->resource->getCompany()->getRuc(),
                'company_name' => $this->resource->getCompany()->getCompanyName()
            ],
            'branch' => [
                'id' => $this->resource->getBranch()->getId(),
                'name' => $this->resource->getBranch()->getName(),
            ],
            'elec_document_type' => [
                'id' => $this->resource->getElecDocumentType()->getId(),
                'cod_sunat' => $this->resource->getElecDocumentType()->getCodSunat(),
                'description' => $this->resource->getElecDocumentType()->getDescription(),
                'abbreviation' => $this->resource->getElecDocumentType()->getAbbreviation(),
            ],
            'dir_document_type' => [
                'id' => $this->resource->getDirDocumentType()->getId(),
                'cod_sunat' => $this->resource->getDirDocumentType()->getCodSunat(),
                'description' => $this->resource->getDirDocumentType()->getDescription(),
                'abbreviation' => $this->resource->getDirDocumentType()->getAbbreviation(),
            ],
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
        ];
    }
}
