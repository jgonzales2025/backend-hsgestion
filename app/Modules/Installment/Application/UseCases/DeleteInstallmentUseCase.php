<?php

namespace App\Modules\Installment\Application\UseCases;

use App\Modules\Installment\Domain\Interface\InstallmentRepositoryInterface as InterfaceInstallmentRepositoryInterface;
use App\Modules\Installment\Domain\Interfaces\InstallmentRepositoryInterface;

class DeleteInstallmentUseCase
{
    public function __construct(private readonly InterfaceInstallmentRepositoryInterface $installmentRepository)
    {
    }

    public function execute(int $saleId): void
    {
        $this->installmentRepository->delete($saleId);
    }
}
