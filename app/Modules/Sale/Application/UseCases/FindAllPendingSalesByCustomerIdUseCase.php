<?php

namespace App\Modules\Sale\Application\UseCases;

use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;

class FindAllPendingSalesByCustomerIdUseCase
{
    public function __construct(private SaleRepositoryInterface $saleRepository){}

    public function execute(int $customerId): ?array
    {
        return $this->saleRepository->findAllPendingSalesByCustomerId($customerId);
    }
}