<?php

namespace App\Modules\Customer\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'record_type_id' => $this->resource->getRecordTypeId(),
            'customer_document_type_id' => $this->resource->getCustomerDocumentTypeId(),
            'document_number' => $this->resource->getDocumentNumber(),
            'company_name' => $this->resource->getCompanyName(),
            'name' => $this->resource->getName(),
            'lastname' => $this->resource->getLastname(),
            'second_lastname' => $this->resource->getSecondLastname(),
            'customer_type' => [
                'id' => $this->resource->getCustomerTypeId(),
                'name' => $this->resource->getCustomerTypeName(),
            ],
            'fax' => $this->resource->getFax(),
            'contact' => $this->resource->getContact(),
            'is_withholding_applicable' => $this->resource->isWithholdingApplicable(),
            'status' => $this->resource->getStatus(),
        ];
    }
}
