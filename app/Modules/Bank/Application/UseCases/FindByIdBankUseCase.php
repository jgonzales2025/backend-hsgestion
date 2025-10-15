<?php

namespace App\Modules\Bank\Application\UseCases;

use App\Modules\Bank\Domain\Entities\Bank;
use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;

readonly class FindByIdBankUseCase
{
    public function __construct(private readonly BankRepositoryInterface $bankRepository){}

    public function execute($id): ?Bank
    {
        return $this->bankRepository->findById($id);
    }
}
