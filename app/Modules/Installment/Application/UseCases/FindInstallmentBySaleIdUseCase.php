<?php

namespace App\Modules\Installment\Application\UseCases;

use App\Modules\Installment\Domain\Interface\InstallmentRepositoryInterface;

class FindInstallmentBySaleIdUseCase
{
    public function __construct(private InstallmentRepositoryInterface $installmentRepository)
    {
    }

    public function execute(int $saleId): ?array
    {
        return $this->installmentRepository->getInstallmentsBySaleId($saleId);
    }
}