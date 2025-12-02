<?php

namespace App\Modules\Installment\Application\UseCases;

use App\Modules\Installment\Application\DTOs\InstallmentDTO;
use App\Modules\Installment\Domain\Entities\Installment;
use App\Modules\Installment\Domain\Interface\InstallmentRepositoryInterface;

class CreateInstallmentUseCase
{
    public function __construct(private InstallmentRepositoryInterface $installmentRepository)
    {
    }

    public function execute(InstallmentDTO $installmentDTO): void
    {
        $installment = new Installment(
            id: 0,
            installment_number: $installmentDTO->installment_number,
            sale_id: $installmentDTO->sale_id,
            amount: $installmentDTO->amount,
            due_date: $installmentDTO->due_date,
        );

        $this->installmentRepository->saveInstallment($installment);
    }
}