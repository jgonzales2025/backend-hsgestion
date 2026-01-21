<?php

namespace App\Modules\Warranty\Infrastructure\Resource;

use App\Modules\DocumentType\Application\UseCases\FindByIdDocumentTypeUseCase;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class WarrantyEntryGuideResource extends JsonResource
{
    public function __construct(
        private readonly DocumentTypeRepositoryInterface $documentRepository,
        private readonly EntryGuide $entryGuide
    ) {
    }

    public function toArray($request): array
    {
        $documentTypeUseCase = new FindByIdDocumentTypeUseCase($this->documentRepository);
        $documentTypes = $documentTypeUseCase->execute($this->entryGuide->getReferenceDocument());

        return [
            'id' => $this->entryGuide->getId(),
            'serie' => $this->entryGuide->getSerie(),
            'correlative' => $this->entryGuide->getCorrelativo(),

            'date' => Carbon::parse($this->entryGuide->getDate())->format('Y-m-d'),
            'reference_purchase_document_id' => $this->entryGuide->getReferenceDocument(),
            'reference_purchase_abbreviation' => $documentTypes->getAbbreviation(),
            'reference_purchase_description' => $documentTypes->getDescription(),
            'reference_purchase_serie' => $this->entryGuide->getReferenceSerie(),
            'reference_purchase_correlative' => $this->entryGuide->getReferenceCorrelative(),

            'supplier' => [
                'id' => $this->entryGuide->getCustomer()->getId(),
                'document_number' => $this->entryGuide->getCustomer()->getDocumentNumber() ?? $this->getCustomer()->getLastname(),
                'name' => $this->entryGuide->getCustomer()->getCompanyName() ??
                    trim($this->entryGuide->getCustomer()->getName() . ' ' .
                        $this->entryGuide->getCustomer()->getLastname() . ' ' .
                        $this->entryGuide->getCustomer()->getSecondLastname()),
            ],
        ];
    }
}