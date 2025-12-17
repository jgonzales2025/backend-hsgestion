<?php

namespace App\Modules\Sale\Application\UseCases;

use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;

readonly class FindAllProformasUseCase
{
    public function __construct(private readonly SaleRepositoryInterface $saleRepository){}

    public function execute(?string $start_date, ?string $end_date, ?int $status, ?string $description)
    {
        return $this->saleRepository->findAllProformas($start_date, $end_date, $status, $description);
    }
}
