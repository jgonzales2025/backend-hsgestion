<?php

namespace App\Modules\DocumentType\Application\UseCases;

use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;

readonly class FindAllDocumentTypesUseCase
{
    public function __construct(private readonly DocumentTypeRepositoryInterface $documentTypeRepository){}

    public function execute(): array
    {
        return $this->documentTypeRepository->findAll();
    }
}
