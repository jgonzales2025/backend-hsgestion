<?php

namespace App\Modules\Warranty\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class WarrantyResource extends JsonResource
{
    public function toArray($request): array
    {
        $customer = $this->resource->getCustomer();
        $supplier = $this->resource->getSupplier();
        $isCompany = $customer->getCustomerDocumentType()->getId() == 2;
        $isSupplierCompany = $supplier->getCustomerDocumentType()->getId() == 2;

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
            'branch_sale' => [
                'id' => $this->resource->getBranchSale()->getId(),
                'name' => $this->resource->getBranchSale()->getName(),
            ],
            'serie' => $this->resource->getSerie(),
            'correlative' => $this->resource->getCorrelative(),
            'article' => [
                'id' => $this->resource->getArticle()->getId(),
                'description' => $this->resource->getArticle()->getDescription(),
            ],
            'serie_art' => $this->resource->getSerieArt(),
            'date' => $this->resource->getDate(),
            'reference_sale' => [
                'id' => $this->resource->getReferenceSale()->getId(),
                'reference_document_type' => [
                    'id' => $this->resource->getReferenceSale()->getDocumentType()->getId(),
                    'name' => $this->resource->getReferenceSale()->getDocumentType()->getDescription(),
                ],
                'serie' => $this->resource->getReferenceSale()->getSerie(),
                'correlative' => $this->resource->getReferenceSale()->getDocumentNumber(),
            ],
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
            'customer_phone' => $this->resource->getCustomerPhone(),
            'customer_email' => $this->resource->getCustomerEmail(),
            'failure_description' => $this->resource->getFailureDescription(),
            'observations' => $this->resource->getObservations(),
            'diagnosis' => $this->resource->getDiagnosis(),
            'supplier' => [
                'id' => $this->resource->getSupplier()->getId(),
                ...($isSupplierCompany ? [
                    'document_number' => $supplier->getDocumentNumber(),
                    'business_name' => $supplier->getCompanyName(),
                ] : [
                    'document_number' => $supplier->getDocumentNumber(),
                    'name' => $supplier->getName(),
                    'lastname' => $supplier->getLastName(),
                    'second_lastname' => $supplier->getSecondLastName(),
                ]),
            ],
            'entry_guide' => [
                'id' => $this->resource->getEntryGuide()->getId(),
                'serie' => $this->resource->getEntryGuide()->getSerie(),
                'correlative' => $this->resource->getEntryGuide()->getCorrelativo(),
            ],
            'contact' => $this->resource->getContact(),
            'follow_up_diagnosis' => $this->resource->getFollowUpDiagnosis(),
            'follow_up_status' => $this->resource->getFollowUpStatus(),
            'solution' => $this->resource->getSolution(),
            'warranty_status' => $this->resource->getWarrantyStatus(),
            'solution_date' => $this->resource->getSolutionDate(),
            'delivery_description' => $this->resource->getDeliveryDescription(),
            'delivery_serie_art' => $this->resource->getDeliverySerieArt(),
            'credit_note_serie' => $this->resource->getCreditNoteSerie(),
            'credit_note_correlative' => $this->resource->getCreditNoteCorrelative(),
            'delivery_date' => $this->resource->getDeliveryDate(),
            'dispatch_note_serie' => $this->resource->getDispatchNoteSerie(),
            'dispatch_note_correlative' => $this->resource->getDispatchNoteCorrelative(),
            'dispatch_note_date' => $this->resource->getDispatchNoteDate(),
        ];
    }
}
