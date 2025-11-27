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

    public function findByCustomerId(int $customer_id): ?array
    {
        $eloquentAdvance = EloquentAdvance::where('customer_id', $customer_id)->where('status', 0)->get();

        if (!$eloquentAdvance) {
            return null;
        }

        return $eloquentAdvance->map(fn($advance) => new Advance(
            id: $advance->id,
            correlative: $advance->correlative,
            customer: $advance->customer->toDomain($advance->customer),
            payment_method: $advance->paymentMethod->toDomain($advance->paymentMethod),
            bank: $advance->bank->toDomain($advance->bank),
            operation_number: $advance->operation_number,
            operation_date: $advance->operation_date,
            parallel_rate: $advance->parallel_rate,
            currency_type: $advance->currencyType->toDomain($advance->currencyType),
            amount: $advance->amount,
            saldo: $advance->saldo
        ))->toArray();
    }
}