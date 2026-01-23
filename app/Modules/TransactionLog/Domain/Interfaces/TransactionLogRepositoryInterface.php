<?php

namespace App\Modules\TransactionLog\Domain\Interfaces;

use App\Modules\TransactionLog\Domain\Entities\TransactionLog;

interface TransactionLogRepositoryInterface
{
    public function findAll(): array;
    public function save(TransactionLog $transactionLog): void;

    public function findByDocument(string $serie, string $correlative): ?array;
}
