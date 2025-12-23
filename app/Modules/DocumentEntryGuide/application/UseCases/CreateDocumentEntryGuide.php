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
        reference_document_id:$documentEntryGuide->reference_document_id,
        reference_serie:$documentEntryGuide->reference_serie,
        reference_correlative:$documentEntryGuide->reference_correlative,

    );
    return $this->documentEntryGuideDTO->create($documentEntryGuide);
   }

}
