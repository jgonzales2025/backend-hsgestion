<?php

namespace App\Modules\Advance\Infrastructure\Persistence;

use App\Modules\Advance\Domain\Entities\Advance;
use App\Modules\Advance\Domain\Entities\UpdateAdvance;
use App\Modules\Advance\Domain\Interfaces\AdvanceRepositoryInterface;
use App\Modules\Advance\Infrastructure\Models\EloquentAdvance;

class EloquentAdvanceRepository implements AdvanceRepositoryInterface
{
    public function save(Advance $advance): void
    {
        $company_id = request()->get('company_id');
        EloquentAdvance::create([
            'company_id' => $company_id,
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
            saldo: $advance->saldo,
            status: $advance->status
        ))->toArray();
    }

    public function findAll(?string $description, int $company_id)
    {
        $eloquentAdvance = EloquentAdvance::where('company_id', $company_id)
            ->when($description, function ($query) use ($description) {
                return $query->whereHas('customer', function ($query) use ($description) {
                    $query->where('name', 'like', "%{$description}%")
                        ->orWhere('company_name', 'like', "%{$description}%")
                        ->orWhere('document_number', 'like', "%{$description}%");
                });
            })
            ->paginate(10);

        $eloquentAdvance->getCollection()->transform(fn($advance) => new Advance(
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
            saldo: $advance->saldo,
            status: $advance->status
        ))->toArray();

        return $eloquentAdvance;
    }

    public function findById(int $id): ?Advance
    {
        $eloquentAdvance = EloquentAdvance::find($id);

        if (!$eloquentAdvance) {
            return null;
        }

        return new Advance (
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
            saldo: $eloquentAdvance->saldo,
            status: $eloquentAdvance->status
        );
    }

    public function toInvalidateAdvance(int $id): void
    {
        EloquentAdvance::where('id', $id)->update(['status' => 0]);
    }

    public function update(UpdateAdvance $advance): void
    {
        EloquentAdvance::where('id', $advance->getId())->update([
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
}
