<?php

namespace App\Modules\DocumentType\Application\UseCases;

use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;

readonly class FindAllForSalesUseCase
{
    public function __construct(private readonly DocumentTypeRepositoryInterface $documentTypeRepository){}

    public function execute(): array
    {
        return $this->documentTypeRepository->findAllForSales();
    }
}
