<?php

namespace App\Modules\Sale\Application\UseCases;

use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;

readonly class FindAllNoteCreditsByCustomerUseCase
{
    public function __construct(private readonly SaleRepositoryInterface $saleRepository){}

    public function execute(int $customerId): array
    {
        return $this->saleRepository->findAllCreditNotesByCustomerId($customerId);
    }
}
