<?php

namespace App\Modules\Sale\Application\UseCases;

use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;

class UpdateStatusSalesUseCase
{
    public function __construct(
        private readonly SaleRepositoryInterface $saleRepository
    ) {
    }

    public function execute(int $id, int $status): void
    {
        $this->saleRepository->updateStatus($id, $status);
    }
}