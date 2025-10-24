<?php

namespace App\Modules\TransactionLog\Domain\Interfaces;

use App\Modules\TransactionLog\Domain\Entities\TransactionLog;

interface TransactionLogRepositoryInterface
{
    public function save(TransactionLog $transactionLog): void;
}
