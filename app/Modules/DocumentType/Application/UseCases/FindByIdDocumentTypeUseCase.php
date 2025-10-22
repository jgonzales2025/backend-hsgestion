<?php

namespace App\Modules\DocumentType\Application\UseCases;

use App\Modules\DocumentType\Domain\Entities\DocumentType;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;

readonly class FindByIdDocumentTypeUseCase
{
    public function __construct(private readonly DocumentTypeRepositoryInterface $documentTypeRepository){}

    public function execute($id): ?DocumentType
    {
        return $this->documentTypeRepository->findById($id);
    }
}
