<?php

namespace App\Modules\Sale\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $customer = $this->resource->getCustomer();
        $isCompany = $customer->getCustomerDocumentTypeId() == 2;
        $isNegative = $this->resource->getDocumentType()->getId() == 7;

        return [
            'id' => $this->resource->getId(),
            'company_id' => $this->resource->getCompany()->getId(),
            'branch_id' => $this->resource->getBranch()->getId(),
            'document_type' => [
                'id' => $this->resource->getDocumentType()->getId(),
                'name' => $this->resource->getDocumentType()->getDescription(),
                'abbreviation' => $this->resource->getDocumentType()->getAbbreviation(),
            ],
            'serie' => $this->resource->getSerie(),
            'document_number' => $this->resource->getDocumentNumber(),
            'parallel_rate' => $this->resource->getParallelRate(),
            'customer' => [
                'id' => $this->resource->getCustomer()->getId(),
                ...($isCompany ? [
                    'document_number' => $customer->getDocumentNumber(),
                    'business_name' => $customer->getCompanyName(),
                ] : [
                    'document_number' => $customer->getDocumentNumber(),
                    'name' => $customer->getName(),
                    'lastname' => $customer->getLastName(),
                    'second_lastname' => $customer->getSecondLastName(),
                ]),
            ],
            'date' => $this->resource->getDate(),
            'due_date' => $this->resource->getDueDate(),
            'days' => $this->resource->getDays(),
            'user' => [
                'id' => $this->resource->getUser()->getId(),
                'username' => $this->resource->getUser()->getUsername(),
                'firstname' => $this->resource->getUser()->getFirstName(),
                'lastname' => $this->resource->getUser()->getLastName()
            ],
            'user_sale' => [
                'id' => $this->resource->getUserSale()->getId(),
                'username' => $this->resource->getUserSale()->getUsername(),
                'firstname' => $this->resource->getUserSale()->getFirstName(),
                'lastname' => $this->resource->getUserSale()->getLastName()
            ],
            'payment_type' => [
                'id' => $this->resource->getPaymentType()->getId(),
                'name' => $this->resource->getPaymentType()->getName(),
            ],
            'observations' => $this->resource->getObservations(),
            'currency_type' => [
                'id' => $this->resource->getCurrencyType()->getId(),
                'name' => $this->resource->getCurrencyType()->getName(),
                'reference'=>($this->resource->getCurrencyType()->getName()) == "SOLES" ? "S/" : "$",
            ],
            'subtotal' => $isNegative ? -$this->resource->getSubtotal() : $this->resource->getSubtotal(),
            'inafecto' => $this->resource->getInafecto(),
            'igv' => $isNegative ? -$this->resource->getIgv() : $this->resource->getIgv(),
            'total' => $isNegative ? -$this->resource->getTotal() : $this->resource->getTotal(),
            'saldo' => $isNegative ? -$this->resource->getSaldo() : $this->resource->getSaldo(),
            'amount_amortized' => $this->resource->getAmountAmortized(),
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            'payment_status' => ($this->resource->getPaymentStatus()) == 1 ? 'Cancelado' : 'Pendiente',
            'is_locked' => $this->resource->getIsLocked(),
            'user_authorized' => $this->resource->getUserAuthorized() ? [
                'id' => $this->resource->getUserAuthorized()->getId(),
                'username' => $this->resource->getUserAuthorized()->getUsername(),
            ] : null,
            'reference_document_type_id' => $this->resource->getReferenceDocumentTypeId() ?? null,
            'reference_serie' => $this->resource->getReferenceSerie() ?? null,
            'reference_correlative' => $this->resource->getReferenceCorrelative() ?? null,
            'note_reason' => $this->resource->getNoteReason() ? [
                'id' => $this->resource->getNoteReason()->getId(),
                'description' => $this->resource->getNoteReason()->getDescription()
            ] : null
        ];
    }
}
