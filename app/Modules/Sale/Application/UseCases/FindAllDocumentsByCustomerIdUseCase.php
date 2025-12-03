<?php

namespace App\Modules\Sale\Application\UseCases;

use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;

class FindAllDocumentsByCustomerIdUseCase
{
    public function __construct(private SaleRepositoryInterface $saleRepository)
    {
    }

    public function execute(int $customerId, ?int $payment_status, ?int $user_sale_id, ?string $start_date, ?string $end_date, ?int $document_type_id)
    {
        return $this->saleRepository->findAllDocumentsByCustomerId($customerId, $payment_status, $user_sale_id, $start_date, $end_date, $document_type_id);
    }
}