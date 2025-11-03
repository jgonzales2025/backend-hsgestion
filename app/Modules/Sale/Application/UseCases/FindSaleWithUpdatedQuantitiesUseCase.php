<?php

namespace App\Modules\Sale\Application\UseCases;

use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;

readonly class FindSaleWithUpdatedQuantitiesUseCase
{
    public function __construct(private readonly SaleRepositoryInterface $saleRepository){}

    public function execute(int $referenceDocumentTypeId, string $referenceSerie, string $referenceCorrelative): array
    {
        return $this->saleRepository->findSaleWithUpdatedQuantities($referenceDocumentTypeId, $referenceSerie, $referenceCorrelative);
    }
}
