<?php

namespace App\Modules\SaleItemSerial\Application\UseCases;

use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\SaleItemSerial\Domain\Interfaces\SaleItemSerialRepositoryInterface;

class FindSaleBySerialUseCase
{
    public function __construct(
        private SaleItemSerialRepositoryInterface $saleItemSerialRepository
    ) {
    }

    public function execute(string $serial): ?Sale
    {
        return $this->saleItemSerialRepository->findSaleBySerial($serial);
    }
}