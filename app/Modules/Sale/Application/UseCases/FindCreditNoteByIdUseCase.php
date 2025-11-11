<?php
namespace App\Modules\Sale\Application\UseCases;

use App\Modules\Sale\Domain\Entities\SaleCreditNote;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;

readonly class FindCreditNoteByIdUseCase
{
    public function __construct(private readonly SaleRepositoryInterface $saleRepository){}

    public function execute(int $id): ?SaleCreditNote
    {
        return $this->saleRepository->findCreditNoteById($id);
    }
}