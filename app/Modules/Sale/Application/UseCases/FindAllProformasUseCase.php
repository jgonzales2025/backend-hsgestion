<?php

namespace App\Modules\Sale\Application\UseCases;

use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;

readonly class FindAllProformasUseCase
{
    public function __construct(private readonly SaleRepositoryInterface $saleRepository){}

    public function execute(): array
    {
        return $this->saleRepository->findAllProformas();
    }
}
