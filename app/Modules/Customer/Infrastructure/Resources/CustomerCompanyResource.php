<?php

namespace App\Modules\Customer\Infrastructure\Resources;

use App\Modules\CustomerAddress\Infrastructure\Resources\CustomerAddressResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerCompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'ruc' => $this->resource->getCustomerDocumentTypeAbbreviation(),
            'company_name' => $this->resource->getCompanyName()
        ];
    }
}
