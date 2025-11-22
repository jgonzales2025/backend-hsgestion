<?php

namespace App\Modules\Sale\Application\UseCases;

use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;

class FindAllSalesByCustomerIdUseCase
{
    public function __construct(private SaleRepositoryInterface $saleRepository){}

    public function execute(int $customerId): ?array
    {
        return $this->saleRepository->findAllSalesByCustomerId($customerId);
    }
}