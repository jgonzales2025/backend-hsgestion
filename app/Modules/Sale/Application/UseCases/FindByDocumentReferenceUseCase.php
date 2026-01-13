<?php

namespace App\Modules\Sale\Application\UseCases;

use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;

class FindByDocumentReferenceUseCase
{
    public function __construct(private readonly SaleRepositoryInterface $saleRepository){}

    public function execute(int $document_type_id, string $serie, string $correlative): bool
    {
        return $this->saleRepository->findByDocumentReference($document_type_id, $serie, $correlative);
    }
}
