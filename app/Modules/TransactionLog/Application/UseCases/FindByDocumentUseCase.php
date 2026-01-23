<?php

namespace App\Modules\TransactionLog\Application\UseCases;

use App\Modules\TransactionLog\Domain\Entities\TransactionLog;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;

class FindByDocumentUseCase
{
    public function __construct(private TransactionLogRepositoryInterface $transactionLogRepository)
    {
    }

    public function execute(string $serie, string $correlative): ?array
    {
        return $this->transactionLogRepository->findByDocument($serie, $correlative);
    }
}