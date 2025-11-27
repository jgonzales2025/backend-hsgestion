<?php
namespace App\Modules\DocumentEntryGuide\application\UseCases;

use App\Modules\DocumentEntryGuide\application\DTOS\DocumentEntryGuideDTO;
use App\Modules\DocumentEntryGuide\Domain\Entities\DocumentEntryGuide;
use App\Modules\DocumentEntryGuide\Domain\Interface\DocumentEntryGuideRepositoryInterface;

class CreateDocumentEntryGuide
{
    public function __construct(private readonly  DocumentEntryGuideRepositoryInterface $documentEntryGuideDTO)
    {
    }
   public function execute(DocumentEntryGuideDTO $documentEntryGuide)
   {
    
    $documentEntryGuide = new DocumentEntryGuide(
        id:0,
        entry_guide_id:$documentEntryGuide->entry_guide_id,
        guide_serie_supplier:$documentEntryGuide->guide_serie_supplier,
        guide_correlative_supplier:$documentEntryGuide->guide_correlative_supplier,
        invoice_serie_supplier:$documentEntryGuide->invoice_serie_supplier,
        invoice_correlative_supplier:$documentEntryGuide->invoice_correlative_supplier,

    );
    return $this->documentEntryGuideDTO->create($documentEntryGuide);
   }

}