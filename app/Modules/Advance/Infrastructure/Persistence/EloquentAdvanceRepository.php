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

    public function findByCustomerId(int $customer_id): ?Advance
    {
        $eloquentAdvance = EloquentAdvance::where('customer_id', $customer_id)->first();

        if (!$eloquentAdvance) {
            return null;
        }

        return new Advance(
            id: $eloquentAdvance->id,
            correlative: $eloquentAdvance->correlative,
            customer: $eloquentAdvance->customer->toDomain($eloquentAdvance->customer),
            payment_method: $eloquentAdvance->paymentMethod->toDomain($eloquentAdvance->paymentMethod),
            bank: $eloquentAdvance->bank->toDomain($eloquentAdvance->bank),
            operation_number: $eloquentAdvance->operation_number,
            operation_date: $eloquentAdvance->operation_date,
            parallel_rate: $eloquentAdvance->parallel_rate,
            currency_type: $eloquentAdvance->currencyType->toDomain($eloquentAdvance->currencyType),
            amount: $eloquentAdvance->amount,
            saldo: $eloquentAdvance->saldo
        );
    }
}