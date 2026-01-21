<?php

namespace App\Modules\SaleItemSerial\Application\UseCases;

use App\Modules\SaleItemSerial\Domain\Interfaces\SaleItemSerialRepositoryInterface;

class UpdateStatusBySerialsUseCase
{
    public function __construct(private readonly SaleItemSerialRepositoryInterface $saleItemSerialRepository){}
    
    public function execute(array $serials): void
    {
        $this->saleItemSerialRepository->updateStatusBySerials($serials);
    }
}