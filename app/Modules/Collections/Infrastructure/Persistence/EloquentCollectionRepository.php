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
        return EloquentCollection::all()
            ->map(fn($collection) => $this->mapToDomain($collection))
            ->toArray();
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
            'status' => $collection->getStatus()
        ]);

        $this->updateSaleBalance($eloquentCollection);

        return $this->mapToDomain($eloquentCollection);
    }

    public function findBySaleId(int $saleId): array
    {
        return EloquentCollection::where('sale_id', $saleId)
            ->get()
            ->map(fn($collection) => $this->mapToDomain($collection))
            ->toArray();
    }

    public function findById(int $id): ?Collection
    {
        $eloquentCollection = EloquentCollection::find($id);

        return $eloquentCollection ? $this->mapToDomain($eloquentCollection) : null;
    }

    public function cancelCharge(int $id): void
    {
        $eloquentCollection = EloquentCollection::findOrFail($id);
        $eloquentCollection->update(['status' => 0]);

        $this->updateSaleBalance($eloquentCollection);

    }

    private function mapToDomain(EloquentCollection $eloquentCollection): Collection
    {
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
            status: $eloquentCollection->status ?? 1
        );
    }

    private function updateSaleBalance(EloquentCollection $collection): void
    {
        DB::statement('CALL sp_actualiza_saldo_venta(?, ?, ?, ?)', [
            $collection->company_id,
            $collection->sale_document_type_id,
            $collection->sale_serie,
            $collection->sale_correlative
        ]);

        $sale = $collection->sale->fresh();
        $sale->payment_status = $sale->saldo == 0 ? 1 : 0;
        $sale->amount_amortized = $sale->total - $sale->saldo;
        $sale->save();
    }
}
