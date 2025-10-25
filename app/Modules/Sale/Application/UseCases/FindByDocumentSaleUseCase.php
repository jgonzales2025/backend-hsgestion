<?php

namespace App\Modules\Sale\Application\UseCases;

use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;

readonly class FindByDocumentSaleUseCase
{
    public function __construct(private readonly SaleRepositoryInterface $saleRepository){}

    public function execute(int $documentTypeId, string $serie, string $correlative): ?Sale
    {
        return $this->saleRepository->findByDocumentSale($documentTypeId, $serie, $correlative);
    }
}
