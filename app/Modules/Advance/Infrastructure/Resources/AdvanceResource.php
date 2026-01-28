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
                'name' => $this->resource->getCustomer()->getCompanyName() ??
                    trim($this->resource->getCustomer()->getName() . ' ' .
                        $this->resource->getCustomer()->getLastname() . ' ' .
                        $this->resource->getCustomer()->getSecondLastname()),
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
            'status' => $this->resource->getStatus() == 1 ? 'Activo':'Anulado',
        ];
    }
}
