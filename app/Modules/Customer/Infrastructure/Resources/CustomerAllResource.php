<?php

namespace App\Modules\Customer\Infrastructure\Resources;

use App\Modules\CustomerAddress\Infrastructure\Resources\CustomerAddressResource;
use App\Modules\CustomerEmail\Infrastructure\Resources\CustomerEmailResource;
use App\Modules\CustomerPhone\Infrastructure\Resources\CustomerPhoneResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerAllResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->resource->getId(),
            'record_type' => [
                'id' => $this->resource->getRecordTypeId(),
                'name' => $this->resource->getRecordTypeName()
            ],
            'customer_document_type' => [
                'id' => $this->resource->getCustomerDocumentType()->getId(),
                'name' => $this->resource->getCustomerDocumentType()->getDescription(),
                'abbreviation' => $this->resource->getCustomerDocumentType()->getAbbreviation(),
            ],
            'document_number' => $this->resource->getDocumentNumber(),
            'company_name' => $this->resource->getCompanyName(),
            'name' => $this->resource->getName(),
            'lastname' => $this->resource->getLastname(),
            'second_lastname' => $this->resource->getSecondLastname(),
            'customer_type' => [
                'id' => $this->resource->getCustomerTypeId(),
                'name' => $this->resource->getCustomerTypeName(),
            ],
            'contact' => $this->resource->getContact(),
            'is_withholding_applicable' => $this->resource->isWithholdingApplicable(),
            'status' => $this->resource->getStatus() == 1 ? 'Activo' : 'Inactivo',
            'phones' => CustomerPhoneResource::collection($this->resource->getPhones()),
            'emails' => CustomerEmailResource::collection($this->resource->getEmails()),
            'addresses' => CustomerAddressResource::collection($this->resource->getAddresses())
        ];
    }
}
