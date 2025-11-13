<?php

namespace App\Modules\Bank\Application\UseCases;

use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;

class UpdateStatusBankUseCase
{
    private BankRepositoryInterface $bankRepository;

    public function __construct(BankRepositoryInterface $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    public function execute(int $bankId, int $status): void
    {
        $this->bankRepository->updateStatus($bankId, $status);
    }
}
