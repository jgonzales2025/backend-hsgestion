<?php

namespace App\Modules\Sale\Application\UseCases;

use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;

readonly class FindByIdSaleUseCase
{
    public function __construct(private readonly SaleRepositoryInterface $saleRepository){}

    public function execute(int $id): ?Sale
    {
        return $this->saleRepository->findById($id);
    }
}
