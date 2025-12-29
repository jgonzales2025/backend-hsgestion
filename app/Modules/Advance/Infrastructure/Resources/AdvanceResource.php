<?php

namespace App\Modules\Advance\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdvanceResource extends JsonResource
{
    public function toArray($request)
    {
        $customer = $this->resource->getCustomer();
        $isCompany = $customer->getCustomerDocumentType()->getId() == 2;
        return [
            'id' => $this->resource->getId(),
            'correlative' => $this->resource->getCorrelative(),
            'customer' => [
                'id' => $this->resource->getCustomer()->getId(),
                ...($isCompany ? [
                    'document_number' => $customer->getDocumentNumber(),
                    'company_name' => $customer->getCompanyName(),
                ] : [
                    'document_number' => $customer->getDocumentNumber(),
                    'name' => $customer->getName(),
                    'lastname' => $customer->getLastName(),
                    'second_lastname' => $customer->getSecondLastName(),
                ])
            ],
            'payment_method' => [
                'id' => $this->resource->getPaymentMethod()->getId(),
                'name' => $this->resource->getPaymentMethod()->getDescription(),
            ],
            'bank' => [
                'id' => $this->resource->getBank()->getId(),
                'name' => $this->resource->getBank()->getName(),
            ],
            'operation_number' => $this->resource->getOperationNumber(),
            'operation_date' => $this->resource->getOperationDate(),
            'parallel_rate' => $this->resource->getParallelRate(),
            'currency_type' => [
                'id' => $this->resource->getCurrencyType()->getId(),
                'name' => $this->resource->getCurrencyType()->getName(),
                'commercial_symbol' => $this->resource->getCurrencyType()->getCommercialSymbol(),
            ],
            'amount' => $this->resource->getAmount(),
            'saldo' => $this->resource->getSaldo(),
        ];
    }
}
