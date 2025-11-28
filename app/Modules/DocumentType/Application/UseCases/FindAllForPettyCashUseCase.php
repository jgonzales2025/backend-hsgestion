<?php

namespace App\Modules\DocumentType\Application\UseCases;

use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;

class FindAllForPettyCashUseCase
{
    public function __construct(
        private DocumentTypeRepositoryInterface $documentTypeRepository
    ) {}

    public function execute(): array
    {
        return $this->documentTypeRepository->findAllForPettyCash();
    }
}