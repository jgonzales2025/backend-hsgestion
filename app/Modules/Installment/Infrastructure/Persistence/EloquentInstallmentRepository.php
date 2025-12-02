<?php

namespace App\Modules\Installment\Infrastructure\Persistence;

use App\Modules\Installment\Domain\Entities\Installment;
use App\Modules\Installment\Domain\Interface\InstallmentRepositoryInterface;
use App\Modules\Installment\Infrastructure\Model\EloquentInstallment;

class EloquentInstallmentRepository implements InstallmentRepositoryInterface
{
    public function saveInstallment(Installment $installment): void
    {
        EloquentInstallment::create([
            'installment_number' => $installment->getInstallmentNumber(),
            'sale_id' => $installment->getSaleId(),
            'amount' => $installment->getAmount(),
            'due_date' => $installment->getDueDate(),
        ]);
    }

    public function getInstallmentsBySaleId(int $saleId): ?array
    {
        $installments = EloquentInstallment::where('sale_id', $saleId)->get();
        
        if (!$installments)
        {
            return null;
        }

        return $installments->map(function ($installment) {
            return new Installment(
                id: $installment['id'],
                sale_id: $installment['sale_id'],
                installment_number: $installment['installment_number'],
                amount: $installment['amount'],
                due_date: $installment['due_date'],
            );
        })->toArray();
    }

    public function delete(int $saleId): void
    {
        EloquentInstallment::where('sale_id', $saleId)->delete();
    }
}