<?php

namespace App\Modules\Bank\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'name' => $this->resource->getName(),
            'account_number' => $this->resource->getAccountNumber(),
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            'currencyType' => [
                'id' => $this->resource->getCurrencyType()->getId(),
                'name' => $this->resource->getCurrencyType()->getName(),
                'commercial_symbol' => $this->resource->getCurrencyType()->getCommercialSymbol(),
            ],
            'user' => [
                'id' => $this->resource->getUser()->getId(),
                'username' => $this->resource->getUser()->getUsername(),
                'firstname' => $this->resource->getUser()->getFirstName(),
            ],
            'company' => [
                'id' => $this->resource->getCompany()->getId(),
                'ruc' => $this->resource->getCompany()->getRuc(),
                'company_name' => $this->resource->getCompany()->getCompanyName(),
            ]
        ];
    }
}
