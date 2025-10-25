<?php

namespace App\Modules\TransactionLog\Application\UseCases;

use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;

readonly class FindAllTransactionLogsUseCase
{
    public function __construct(private readonly TransactionLogRepositoryInterface $transactionLogRepository){}

    public function execute(): array
    {
        return $this->transactionLogRepository->findAll();
    }
}
