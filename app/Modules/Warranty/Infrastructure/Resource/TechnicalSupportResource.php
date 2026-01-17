<?php

namespace App\Modules\Warranty\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class TechnicalSupportResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'document_type_warranty_id' => $this->resource->getDocumentTypeWarrantyId(),
            'company' => [
                'id' => $this->resource->getCompany()->getId(),
                'name' => $this->resource->getCompany()->getCompanyName(),
            ],
            'branch' => [
                'id' => $this->resource->getBranch()->getId(),
                'name' => $this->resource->getBranch()->getName(),
            ],
            'serie' => $this->resource->getSerie(),
            'correlative' => $this->resource->getCorrelative(),
            'date' => $this->resource->getDate(),
            'customer_phone' => $this->resource->getCustomerPhone(),
            'customer_email' => $this->resource->getCustomerEmail(),
            'failure_description' => $this->resource->getFailureDescription(),
            'observations' => $this->resource->getObservations(),
            'diagnosis' => $this->resource->getDiagnosis(),
            'contact' => $this->resource->getContact(),
        ];
    }
}