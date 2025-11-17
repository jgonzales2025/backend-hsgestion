<?php

namespace App\Modules\SaleItemSerial\Application\UseCases;

use App\Modules\SaleItemSerial\Domain\Interfaces\SaleItemSerialRepositoryInterface;

class DeleteSaleItemSerialBySaleIdUseCase
{
    private $saleItemSerialRepository;

    public function __construct(SaleItemSerialRepositoryInterface $saleItemSerialRepository)
    {
        $this->saleItemSerialRepository = $saleItemSerialRepository;
    }

    public function execute(int $saleId): void
    {
        $this->saleItemSerialRepository->deleteSerialsBySaleId($saleId);
    }
}
