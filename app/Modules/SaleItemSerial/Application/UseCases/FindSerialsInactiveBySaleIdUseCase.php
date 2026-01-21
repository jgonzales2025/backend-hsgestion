<?php

namespace App\Modules\SaleItemSerial\Application\UseCases;

use App\Modules\SaleItemSerial\Domain\Interfaces\SaleItemSerialRepositoryInterface;

class FindSerialsInactiveBySaleIdUseCase
{
    public function __construct(private readonly SaleItemSerialRepositoryInterface $saleItemSerialRepository)
    {
    }

    public function execute(int $saleId): array
    {
        return $this->saleItemSerialRepository->findSerialsInactiveBySaleId($saleId);
    }
}