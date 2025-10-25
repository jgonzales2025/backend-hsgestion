<?php

namespace App\Modules\Collections\Infrastructure\Persistence;

use App\Modules\Collections\Domain\Entities\Collection;
use App\Modules\Collections\Domain\Interfaces\CollectionRepositoryInterface;
use App\Modules\Collections\Infrastructure\Models\EloquentCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EloquentCollectionRepository implements CollectionRepositoryInterface
{

    public function findAll(): array
    {
        $collections = EloquentCollection::all();
        if ($collections->isEmpty()) {
            return [];
        }
        return $collections->map(function ($collection) {
            return new Collection(
                id: $collection->id,
                company_id: $collection->company_id,
                sale_id: $collection->sale_id,
                sale_document_type_id: $collection->sale_document_type_id,
                sale_serie: $collection->sale_serie,
                sale_correlative: $collection->sale_correlative,
                payment_method: $collection->paymentMethod->toDomain($collection->paymentMethod),
                payment_date: $collection->payment_date,
                currency_type_id: $collection->currency_type_id,
                parallel_rate: $collection->parallel_rate,
                amount: $collection->amount,
                change: $collection->change,
                digital_wallet_id: $collection->digital_wallet_id,
                bank_id: $collection->bank_id,
                operation_date: $collection->operation_date,
                operation_number: $collection->operation_number,
                lote_number: $collection->lote_number,
                for_digits: $collection->for_digits,
            );
        })->toArray();
    }

    public function save(Collection $collection): ?Collection
    {
        $eloquentCollection = EloquentCollection::create([
            'company_id' => $collection->getCompanyId(),
            'sale_id' => $collection->getSaleId(),
            'sale_document_type_id' => $collection->getSaleDocumentTypeId(),
            'sale_serie' => $collection->getSaleSerie(),
            'sale_correlative' => $collection->getSaleCorrelative(),
            'payment_method_id' => $collection->getPaymentMethod()->getId(),
            'payment_date' => $collection->getPaymentDate(),
            'currency_type_id' => $collection->getCurrencyTypeId(),
            'parallel_rate' => $collection->getParallelRate(),
            'amount' => $collection->getAmount(),
            'change' => $collection->getChange(),
            'digital_wallet_id' => $collection->getDigitalWalletId(),
            'bank_id' => $collection->getBankId(),
            'operation_date' => $collection->getOperationDate(),
            'operation_number' => $collection->getOperationNumber(),
            'lote_number' => $collection->getLoteNumber(),
            'for_digits' => $collection->getForDigits(),
        ]);

        DB::statement('CALL sp_actualiza_saldo_venta(?, ?, ?, ?)', [
            $eloquentCollection->company_id,
            $eloquentCollection->sale_document_type_id,
            $eloquentCollection->sale_serie,
            $eloquentCollection->sale_correlative
        ]);

        $sale = $eloquentCollection->sale->fresh();
        $sale->payment_status = $sale->saldo == 0 ? 1 : 0;
        $sale->amount_amortized = $sale->total - $sale->saldo;
        $sale->save();

        return new Collection(
            id: $eloquentCollection->id,
            company_id: $eloquentCollection->company_id,
            sale_id: $eloquentCollection->sale_id,
            sale_document_type_id: $eloquentCollection->sale_document_type_id,
            sale_serie: $eloquentCollection->sale_serie,
            sale_correlative: $eloquentCollection->sale_correlative,
            payment_method: $eloquentCollection->paymentMethod->toDomain($eloquentCollection->paymentMethod),
            payment_date: $eloquentCollection->payment_date,
            currency_type_id: $eloquentCollection->currency_type_id,
            parallel_rate: $eloquentCollection->parallel_rate,
            amount: $eloquentCollection->amount,
            change: $eloquentCollection->change,
            digital_wallet_id: $eloquentCollection->digital_wallet_id,
            bank_id: $eloquentCollection->bank_id,
            operation_date: $eloquentCollection->operation_date,
            operation_number: $eloquentCollection->operation_number,
            lote_number: $eloquentCollection->lote_number,
            for_digits: $eloquentCollection->for_digits,
            status: $eloquentCollection->status,
        );
    }

    public function findBySaleId(int $saleId): array
    {
        $eloquentCollections = EloquentCollection::where('sale_id', $saleId)->get();

        return $eloquentCollections->map(function ($collection) {
            return new Collection(
                id: $collection->id,
                company_id: $collection->company_id,
                sale_id: $collection->sale_id,
                sale_document_type_id: $collection->sale_document_type_id,
                sale_serie: $collection->sale_serie,
                sale_correlative: $collection->sale_correlative,
                payment_method: $collection->paymentMethod->toDomain($collection->paymentMethod),
                payment_date: $collection->payment_date,
                currency_type_id: $collection->currency_type_id,
                parallel_rate: $collection->parallel_rate,
                amount: $collection->amount,
                change: $collection->change,
                digital_wallet_id: $collection->digital_wallet_id,
                bank_id: $collection->bank_id,
                operation_date: $collection->operation_date,
                operation_number: $collection->operation_number,
                lote_number: $collection->lote_number,
                for_digits: $collection->for_digits,
                status: $collection->status
            );
        })->toArray();
    }

    public function findById(int $id): ?Collection
    {
        // TODO: Implement findById() method.
    }
}
