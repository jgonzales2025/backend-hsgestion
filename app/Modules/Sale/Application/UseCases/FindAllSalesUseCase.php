<?php

namespace App\Modules\Sale\Application\UseCases;

use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;

readonly class FindAllSalesUseCase
{
    public function __construct(private readonly SaleRepositoryInterface $saleRepository){}

    public function execute(int $companyId, ?string $start_date, ?string $end_date, ?string $description, ?int $status, ?int $payment_status, ?int $document_type_id)
    {
        return $this->saleRepository->findAll($companyId, $start_date, $end_date, $description, $status, $payment_status, $document_type_id);
    }
}
