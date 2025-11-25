<?php

namespace App\Modules\PurchaseOrder\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $supplier = $this->resource->getSupplier();
        $isCompany = $supplier->getCustomerDocumentTypeId() == 2;
        
        return [
            'id' => $this->resource->getId(),
            'branch' => [
                'id' => $this->resource->getBranch()->getId(),
                'name' => $this->resource->getBranch()->getName(),
            ],
            'serie' => $this->resource->getSerie(),
            'correlative' => $this->resource->getCorrelative(),
            'date' => $this->resource->getDate(),
            'delivery_date' => $this->resource->getDeliveryDate(),
            'due_date' => $this->resource->getDueDate(),
            'days' => $this->resource->getDays(),
            'contact_name' => $this->resource->getContactName(),
            'contact_phone' => $this->resource->getContactPhone(),
            'currency_type' => [
                'id' => $this->resource->getCurrencyType()->getId(),
                'name' => $this->resource->getCurrencyType()->getName(),
            ],
            'payment_type' => [
                'id' => $this->resource->getPaymentType()->getId(),
                'name' => $this->resource->getPaymentType()->getName(),
            ],
            'order_number_supplier' => $this->resource->getOrderNumberSupplier() ?? null,
            'supplier' => [
                'id' => $this->resource->getSupplier()->getId(),
                ...($isCompany ? [
                    'document_number' => $supplier->getDocumentNumber(),
                    'company_name' => $supplier->getCompanyName(),
                ] : [
                    'document_number' => $supplier->getDocumentNumber(),
                    'name' => $supplier->getName(),
                    'lastname' => $supplier->getLastName(),
                    'second_lastname' => $supplier->getSecondLastName(),
                ]),
            ],
            'status' => $this->resource->getStatus(),
            'observations' => $this->resource->getObservations(),
            'subtotal' => $this->resource->getSubtotal() ?? null,
            'igv' => $this->resource->getIgv() ?? null,
            'total' => $this->resource->getTotal() ?? null,
        ];
    }
}
