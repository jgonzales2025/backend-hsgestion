<?php
namespace App\Modules\DocumentEntryGuide\application\UseCases;

use App\Modules\DocumentEntryGuide\application\DTOS\DocumentEntryGuideDTO;
use App\Modules\DocumentEntryGuide\Domain\Entities\DocumentEntryGuide;
use App\Modules\DocumentEntryGuide\Domain\Interface\DocumentEntryGuideRepositoryInterface;
use App\Modules\DocumentType\Application\UseCases\FindByIdDocumentTypeUseCase;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;

class CreateDocumentEntryGuide
{
    public function __construct(private readonly  DocumentEntryGuideRepositoryInterface $documentEntryGuideDTO,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository,
    )
    {
    }
   public function execute(DocumentEntryGuideDTO $documentEntryGuide)
   {
    $usecasDocument = new FindByIdDocumentTypeUseCase($this->documentTypeRepository);
    $documentType = $usecasDocument->execute($documentEntryGuide->reference_document_id);
    
    $documentEntryGuide = new DocumentEntryGuide(
        id:0,
        entry_guide_id:$documentEntryGuide->entry_guide_id,
        reference_document:$documentType,
        reference_serie:$documentEntryGuide->reference_serie,
        reference_correlative:$documentEntryGuide->reference_correlative,

    );
    return $this->documentEntryGuideDTO->create($documentEntryGuide);
   }

}
