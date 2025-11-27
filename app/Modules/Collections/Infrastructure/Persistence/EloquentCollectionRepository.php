<?php

namespace App\Modules\Collections\Infrastructure\Persistence;

use App\Modules\Advance\Infrastructure\Models\EloquentAdvance;
use App\Modules\Collections\Domain\Entities\BulkCollection;
use App\Modules\Collections\Domain\Entities\Collection;
use App\Modules\Collections\Domain\Interfaces\CollectionRepositoryInterface;
use App\Modules\Collections\Infrastructure\Models\EloquentCollection;
use App\Modules\Sale\Infrastructure\Models\EloquentSale;
use Illuminate\Support\Facades\DB;

class EloquentCollectionRepository implements CollectionRepositoryInterface
{

    public function findAll(): array
    {
        return EloquentCollection::all()->sortByDesc('created_at')
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
            ->sortByDesc('created_at')
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

    public function saveCollectionCreditNote(Collection $collection): ?Collection
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
            'status' => $collection->getStatus(),
            'credit_document_type_id' => $collection->getCreditDocumentTypeId(),
            'credit_serie' => $collection->getCreditSerie(),
            'credit_correlative' => $collection->getCreditCorrelative(),
        ]);

        $this->updateSaleBalance($eloquentCollection);

        $this->updateCreditNotePaymentStatus($eloquentCollection);

        return $this->mapToDomain($eloquentCollection);
    }

    public function saveBulkCollection(BulkCollection $collection, array $data): void
    {
        foreach ($data as $item) {
            $eloquentCollection = EloquentCollection::create([
                'company_id' => $collection->getCompanyId(),
                'sale_id' => $item['sale_id'],
                'sale_document_type_id' => $item['sale_document_type_id'],
                'sale_serie' => $item['serie'],
                'sale_correlative' => $item['correlative'],
                'payment_method_id' => $collection->getPaymentMethodId(),
                'payment_date' => $collection->getPaymentDate(),
                'currency_type_id' => $collection->getCurrencyTypeId(),
                'parallel_rate' => $collection->getParallelRate(),
                'amount' => $item['amount'],
                'bank_id' => $collection->getBankId(),
                'operation_date' => $collection->getOperationDate(),
                'operation_number' => $collection->getOperationNumber(),
                'advance_id' => $collection->getAdvanceId()
            ]);
            $eloquentCollection->refresh();

            // Siempre actualizar el saldo de la venta
            $this->updateSaleBalance($eloquentCollection);

            // Si hay advance_id, también actualizar el saldo del anticipo
            if ($collection->getAdvanceId() !== null) {
                $this->updateAdvanceBalance($collection->getAdvanceId());
            }

        }
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
            status: $eloquentCollection->status ?? 1,
            credit_document_type_id: $eloquentCollection->credit_document_type_id,
            credit_serie: $eloquentCollection->credit_serie,
            credit_correlative: $eloquentCollection->credit_correlative
        );
    }

    private function updateSaleBalance(EloquentCollection $collection): void
    {
        DB::statement('CALL update_sale_balance(?, ?, ?, ?)', [
            $collection->company_id,
            $collection->sale_document_type_id,
            $collection->sale_serie,
            $collection->sale_correlative
        ]);

        // Consultar explícitamente la venta original usando los campos sale_*
        $sale = EloquentSale::where('company_id', $collection->company_id)
            ->where('document_type_id', $collection->sale_document_type_id)
            ->where('serie', $collection->sale_serie)
            ->where('document_number', $collection->sale_correlative)
            ->first();

        if ($sale) {
            $sale = $sale->fresh();
            $sale->payment_status = $sale->saldo == 0 ? 1 : 0;
            $sale->amount_amortized = $sale->total - $sale->saldo;
            $sale->save();
        }
    }

    private function updateAdvanceBalance(int $advanceId): void
    {
        DB::statement('CALL update_advance_balance(?)', [$advanceId]);
        $advance = EloquentAdvance::where('id', $advanceId)->first();
        $advance->status = $advance->saldo == 0 ? 1 : 0;
        $advance->save();
    }

    private function updateCreditNotePaymentStatus(EloquentCollection $collection): void
    {
        // Ejecuta el SP para recalcular el saldo de la nota de crédito
        if ($collection->credit_document_type_id && $collection->credit_serie && $collection->credit_correlative) {
            DB::statement('CALL update_sale_balance(?, ?, ?, ?)', [
                $collection->company_id,
                $collection->credit_document_type_id,
                $collection->credit_serie,
                $collection->credit_correlative
            ]);
        }

        // Actualiza el estado de pago y monto amortizado de la nota de crédito
        $creditNote = EloquentSale::where('company_id', $collection->company_id)
            ->where('document_type_id', $collection->credit_document_type_id)
            ->where('serie', $collection->credit_serie)
            ->where('document_number', $collection->credit_correlative)
            ->first();

        if ($creditNote) {
            $creditNote = $creditNote->fresh();
            $creditNote->payment_status = $creditNote->saldo == 0 ? 1 : 0;
            $creditNote->amount_amortized = $creditNote->total - $creditNote->saldo;
            $creditNote->save();
        }

        $creditNote = EloquentSale::where('company_id', $collection->company_id)
            ->where('document_type_id', $collection->credit_document_type_id)
            ->where('serie', $collection->credit_serie)
            ->where('document_number', $collection->credit_correlative)
            ->first();

        if ($creditNote) {
            $creditNote = $creditNote->fresh();
            $creditNote->payment_status = $creditNote->saldo == 0 ? 1 : 0;
            $creditNote->amount_amortized = $creditNote->total - $creditNote->saldo;
            $creditNote->save();
        }
    }
}
