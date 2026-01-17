<?php

namespace App\Modules\Warranty\Infrastructure\Resource;

use App\Modules\DocumentType\Application\UseCases\FindByIdDocumentTypeUseCase;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\Warranty\Domain\Entities\Warranty;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class WarrantyResource extends JsonResource
{
    public function __construct(
        private readonly Warranty $warranty,
        private readonly DocumentTypeRepositoryInterface $documentRepository
    )
    {}

    public function toArray($request): array
    {
        $documentTypeUseCase = new FindByIdDocumentTypeUseCase($this->documentRepository);
        $documentTypes = $documentTypeUseCase->execute($this->warranty->getEntryGuide()->getReferenceDocument());

        return [
            'id' => $this->warranty->getId(),
            'document_type_warranty_id' => $this->warranty->getDocumentTypeWarrantyId(),
            'company' => [
                'id' => $this->warranty->getCompany()->getId(),
                'name' => $this->warranty->getCompany()->getCompanyName(),
            ],
            'branch' => [
                'id' => $this->warranty->getBranch()->getId(),
                'name' => $this->warranty->getBranch()->getName(),
            ],
            'branch_sale' => [
                'id' => $this->warranty->getBranchSale()->getId(),
                'name' => $this->warranty->getBranchSale()->getName(),
            ],
            'serie' => $this->warranty->getSerie(),
            'correlative' => $this->warranty->getCorrelative(),
            'article' => [
                'id' => $this->warranty->getArticle()->getId(),
                'description' => $this->warranty->getArticle()->getDescription(),
            ],
            'serie_art' => $this->warranty->getSerieArt(),
            'date' => $this->warranty->getDate(),
            'reference_sale' => [
                'id' => $this->warranty->getReferenceSale()->getId(),
                'reference_document_type' => [
                    'id' => $this->warranty->getReferenceSale()->getDocumentType()->getId(),
                    'name' => $this->warranty->getReferenceSale()->getDocumentType()->getDescription(),
                    'abbreviation' => $this->warranty->getReferenceSale()->getDocumentType()->getAbbreviation(),
                ],
                'serie' => $this->warranty->getReferenceSale()->getSerie(),
                'correlative' => $this->warranty->getReferenceSale()->getDocumentNumber(),
                'date' => Carbon::parse($this->warranty->getReferenceSale()->getDate())->format('d/m/Y'),
            ],
            'customer' => [
                'id' => $this->warranty->getCustomer()->getId(),
                'name' => $this->warranty->getCustomer()->getCompanyName() ??
                    trim($this->warranty->getCustomer()->getName() . ' ' .
                        $this->warranty->getCustomer()->getLastname() . ' ' .
                        $this->warranty->getCustomer()->getSecondLastname()),
            ],
            'customer_phone' => $this->warranty->getCustomerPhone(),
            'customer_email' => $this->warranty->getCustomerEmail(),
            'failure_description' => $this->warranty->getFailureDescription(),
            'observations' => $this->warranty->getObservations(),
            'diagnosis' => $this->warranty->getDiagnosis(),
            'supplier' => [
                'id' => $this->warranty->getSupplier()->getId(),
                'name' => $this->warranty->getCustomer()->getCompanyName() ??
                    trim($this->warranty->getCustomer()->getName() . ' ' .
                        $this->warranty->getCustomer()->getLastname() . ' ' .
                        $this->warranty->getCustomer()->getSecondLastname()),
            ],
            'entry_guide' => [
                'id' => $this->warranty->getEntryGuide()->getId(),
                'serie' => $this->warranty->getEntryGuide()->getSerie(),
                'correlative' => $this->warranty->getEntryGuide()->getCorrelativo(),
                'date' => $this->warranty->getEntryGuide()->getDate(),
                'reference_purchase_document_id' => $this->warranty->getEntryGuide()->getReferenceDocument(),
                'reference_purchase_abbreviation' => $documentTypes->getAbbreviation(),
                'reference_purchase_description' => $documentTypes->getDescription(),
                'reference_purchase_serie' => $this->warranty->getEntryGuide()->getReferenceSerie(),
                'reference_purchase_correlative' => $this->warranty->getEntryGuide()->getReferenceCorrelative(),
            ],
            'contact' => $this->warranty->getContact(),
            'follow_up_diagnosis' => $this->warranty->getFollowUpDiagnosis(),
            'follow_up_status' => $this->warranty->getFollowUpStatus(),
            'solution' => $this->warranty->getSolution(),
            'warranty_status' => $this->warranty->getWarrantyStatus(),
            'solution_date' => $this->warranty->getSolutionDate(),
            'delivery_description' => $this->warranty->getDeliveryDescription(),
            'delivery_serie_art' => $this->warranty->getDeliverySerieArt(),
            'credit_note_serie' => $this->warranty->getCreditNoteSerie(),
            'credit_note_correlative' => $this->warranty->getCreditNoteCorrelative(),
            'delivery_date' => Carbon::parse($this->warranty->getDeliveryDate())->format('d/m/Y'),
            'dispatch_note_serie' => $this->warranty->getDispatchNoteSerie(),
            'dispatch_note_correlative' => $this->warranty->getDispatchNoteCorrelative(),
            'dispatch_note_date' => Carbon::parse($this->warranty->getDispatchNoteDate())->format('d/m/Y'),
        ];
    }
}
