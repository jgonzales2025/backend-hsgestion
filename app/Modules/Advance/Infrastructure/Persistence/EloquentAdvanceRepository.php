<?php

namespace App\Modules\Advance\Infrastructure\Persistence;

use App\Modules\Advance\Domain\Entities\Advance;
use App\Modules\Advance\Domain\Interfaces\AdvanceRepositoryInterface;
use App\Modules\Advance\Infrastructure\Models\EloquentAdvance;

class EloquentAdvanceRepository implements AdvanceRepositoryInterface
{
    public function save(Advance $advance): void
    {
        EloquentAdvance::create([
            'correlative' => $advance->getCorrelative(),
            'customer_id' => $advance->getCustomer()->getId(),
            'payment_method_id' => $advance->getPaymentMethod()->getId(),
            'bank_id' => $advance->getBank()->getId(),
            'operation_number' => $advance->getOperationNumber(),
            'operation_date' => $advance->getOperationDate(),
            'parallel_rate' => $advance->getParallelRate(),
            'currency_type_id' => $advance->getCurrencyType()->getId(),
            'amount' => $advance->getAmount(),
            'saldo' => $advance->getAmount()
        ]);
    }

    public function getLastDocumentNumber(): ?string
    {
        return EloquentAdvance::orderBy('id', 'desc')->first()->correlative ?? null;
    }
}