<?php

namespace App\Modules\Purchases\Infrastructure\Persistence;

use App\Modules\Purchases\Domain\Entities\Purchase;
use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;
use App\Modules\Purchases\Infrastructure\Models\EloquentPurchase;
use Illuminate\Support\Facades\DB;

class EloquentPurchaseRepository implements PurchaseRepositoryInterface
{
    public function getLastDocumentNumber(): ?string
    {
        $purchase = EloquentPurchase::all()
            ->sortByDesc('correlative')
            ->first();

        return $purchase?->correlative;
    }

    public function findAll(): array
    {
        $eloquentpurchase = EloquentPurchase::with(['paymentMethod', 'branches', 'customers', 'currencyType'])
            ->orderByDesc('id')
            ->get();

        return $eloquentpurchase->map(function ($purchase) {
            return new Purchase(
                id: $purchase->id,
                company_id: $purchase->company_id,
                branch: $purchase->branches->toDomain($purchase->branches),
                supplier: $purchase->customers->toDomain($purchase->customers),
                serie: $purchase->serie,
                correlative: $purchase->correlative,
                exchange_type: $purchase->exchange_type,
                methodpaymentO: $purchase->paymentMethod->toDomain($purchase->paymentMethod),
                currency: $purchase->currencyType->toDomain($purchase->currencyType),
                date: $purchase->date,
                date_ven: $purchase->date_ven,
                days: $purchase->days,
                observation: $purchase->observation,
                detraccion: $purchase->detraccion,
                fech_detraccion: $purchase->fech_detraccion,
                amount_detraccion: $purchase->amount_detraccion,
                is_detracion: $purchase->is_detracion,
                subtotal: $purchase->subtotal,
                total_desc: $purchase->total_desc,
                inafecto: $purchase->inafecto,
                igv: $purchase->igv,
                total: $purchase->total,
                is_igv: $purchase->is_igv,
                type_document_id: $purchase->document_type_id,
                reference_serie: $purchase->reference_serie,
                reference_correlative: $purchase->reference_correlative,
                saldo: $purchase->saldo,

            );
        })->toArray();
    }
    public function findById(int $id): ?Purchase
    {
        $eloquentpurchase = EloquentPurchase::with(['paymentMethod', 'branches', 'customers', 'currencyType'])->find($id);

        if (!$eloquentpurchase) {
            return null;
        }
        return new Purchase(
            id: $eloquentpurchase->id,
            company_id: $eloquentpurchase->company_id,
            branch: $eloquentpurchase->branches->toDomain($eloquentpurchase->branches),
            supplier: $eloquentpurchase->customers->toDomain($eloquentpurchase->customers),
            serie: $eloquentpurchase->serie,
            correlative: $eloquentpurchase->correlative,
            exchange_type: $eloquentpurchase->exchange_type,
            methodpaymentO: $eloquentpurchase->paymentMethod->toDomain($eloquentpurchase->paymentMethod),
            currency: $eloquentpurchase->currencyType->toDomain($eloquentpurchase->currencyType),
            date: $eloquentpurchase->date,
            date_ven: $eloquentpurchase->date_ven,
            days: $eloquentpurchase->days,
            observation: $eloquentpurchase->observation,
            detraccion: $eloquentpurchase->detraccion,
            fech_detraccion: $eloquentpurchase->fech_detraccion,
            amount_detraccion: $eloquentpurchase->amount_detraccion,
            is_detracion: $eloquentpurchase->is_detracion,
            subtotal: $eloquentpurchase->subtotal,
            total_desc: $eloquentpurchase->total_desc,
            inafecto: $eloquentpurchase->inafecto,
            igv: $eloquentpurchase->igv,
            total: $eloquentpurchase->total,
            is_igv: $eloquentpurchase->is_igv,
            type_document_id: $eloquentpurchase->document_type_id,
            reference_serie: $eloquentpurchase->reference_serie,
            reference_correlative: $eloquentpurchase->reference_correlative,
            saldo: $eloquentpurchase->saldo,
        );
    }
    public function save(Purchase $purchase): ?Purchase
    {
        $eloquentpurchase = EloquentPurchase::create([
            'company_id' => $purchase->getCompanyId(),
            'branch_id' => $purchase->getBranch()->getId(),
            'supplier_id' => $purchase->getSupplier()->getId(),
            'serie' => $purchase->getSerie(),
            'correlative' => $purchase->getCorrelative(),
            'exchange_type' => $purchase->getExchangeType(),
            'methodpayment' => $purchase->getMethodpayment()->getId(),
            'currency' => $purchase->getCurrency()->getId(),
            'date' => $purchase->getDate(),
            'date_ven' => $purchase->getDateVen(),
            'days' => $purchase->getDays(),
            'observation' => $purchase->getObservation(),
            'detraccion' => $purchase->getDetraccion(),
            'fech_detraccion' => $purchase->getFechDetraccion(),
            'amount_detraccion' => $purchase->getAmountDetraccion(),
            'is_detracion' => $purchase->getIsDetracion(),
            'subtotal' => $purchase->getSubtotal(),
            'total_desc' => $purchase->getTotalDesc(),
            'inafecto' => $purchase->getInafecto(),
            'igv' => $purchase->getIgv(),
            'total' => $purchase->getTotal(),
            'is_igv' => $purchase->getIsIgv(),
            'document_type_id' => $purchase->getTypeDocumentId(),
            'reference_serie' => $purchase->getReferenceSerie(),
            'reference_correlative' => $purchase->getReferenceCorrelative(),
            'saldo' => $purchase->getTotal(),
        ]);
 
        DB::statement(
            "CALL update_purchase_balance(?, ?, ?, ?, ?)",
            [
               (int) $purchase->getCompanyId(),
               (int) $purchase->getSupplier()->getId(),
               (int) $purchase->getTypeDocumentId(),
               (string) $purchase->getReferenceSerie(),
               (string) $purchase->getReferenceCorrelative(),
            ]
        );
        return new Purchase(
            id: $eloquentpurchase->id,
            branch: $eloquentpurchase->branches->toDomain($eloquentpurchase->branches),
            supplier: $eloquentpurchase->customers->toDomain($eloquentpurchase->customers),
            serie: $eloquentpurchase->serie,
            correlative: $eloquentpurchase->correlative,
            exchange_type: $eloquentpurchase->exchange_type,
            methodpaymentO: $eloquentpurchase->paymentMethod->toDomain($eloquentpurchase->paymentMethod),
            currency: $eloquentpurchase->currencyType->toDomain($eloquentpurchase->currencyType),
            date: $eloquentpurchase->date,
            date_ven: $eloquentpurchase->date_ven,
            days: $eloquentpurchase->days,
            observation: $eloquentpurchase->observation,
            detraccion: $eloquentpurchase->detraccion,
            fech_detraccion: $eloquentpurchase->fech_detraccion,
            amount_detraccion: $eloquentpurchase->amount_detraccion,
            is_detracion: $eloquentpurchase->is_detracion,
            subtotal: $eloquentpurchase->subtotal,
            total_desc: $eloquentpurchase->total_desc,
            inafecto: $eloquentpurchase->inafecto,
            igv: $eloquentpurchase->igv,
            total: $eloquentpurchase->total,
            is_igv: $eloquentpurchase->is_igv,
            type_document_id: $eloquentpurchase->document_type_id,
            reference_serie: $eloquentpurchase->reference_serie,
            reference_correlative: $eloquentpurchase->reference_correlative,
            company_id: $eloquentpurchase->company_id,
            saldo: $eloquentpurchase->saldo,
        );
    }
    public function update(Purchase $purchase): ?Purchase
    {
        $purchaseUpdtate = EloquentPurchase::find($purchase->getId());
        if (!$purchaseUpdtate) {
            return null;
        }
        // DB::statement(
        //     "CALL update_purchase_balance(?, ?, ?, ?, ?)",
        //     [
        //         $purchase->getCompanyId(),
        //         $purchase->getSupplier()->getId(),
        //         $purchase->getTypeDocumentId(),
        //         $purchase->getReferenceSerie(),
        //         $purchase->getReferenceCorrelative(),
        //     ]
        // );
        $purchaseUpdtate->update([
            'branch_id' => $purchase->getBranch()->getId(),
            'supplier_id' => $purchase->getSupplier()->getId(),
            'serie' => $purchase->getSerie(),
            'correlative' => $purchase->getCorrelative(),
            'exchange_type' => $purchase->getExchangeType(),
            'methodpayment' => $purchase->getMethodpayment()->getId(),
            'currency' => $purchase->getCurrency()->getId(),
            'date' => $purchase->getDate(),
            'date_ven' => $purchase->getDateVen(),
            'days' => $purchase->getDays(),
            'observation' => $purchase->getObservation(),
            'detraccion' => $purchase->getDetraccion(),
            'fech_detraccion' => $purchase->getFechDetraccion(),
            'amount_detraccion' => $purchase->getAmountDetraccion(),
            'is_detracion' => $purchase->getIsDetracion(),
            'subtotal' => $purchase->getSubtotal(),
            'total_desc' => $purchase->getTotalDesc(),
            'inafecto' => $purchase->getInafecto(),
            'igv' => $purchase->getIgv(),
            'total' => $purchase->getTotal(),
            'is_igv' => $purchase->getIsIgv(),
            'document_type_id' => $purchase->getTypeDocumentId(),
            'reference_serie' => $purchase->getReferenceSerie(),
            'reference_correlative' => $purchase->getReferenceCorrelative(),
            'company_id' => $purchase->getCompanyId(),
            'saldo' => $purchase->getTotal(),
        ]);
        return new Purchase(
            id: $purchaseUpdtate->id,
            company_id: $purchaseUpdtate->company_id,
            branch: $purchaseUpdtate->branches->toDomain($purchaseUpdtate->branches),
            supplier: $purchaseUpdtate->customers->toDomain($purchaseUpdtate->customers),
            serie: $purchaseUpdtate->serie,
            correlative: $purchaseUpdtate->correlative,
            exchange_type: $purchaseUpdtate->exchange_type,
            methodpaymentO: $purchaseUpdtate->paymentMethod->toDomain($purchaseUpdtate->paymentMethod),
            currency: $purchaseUpdtate->currencyType->toDomain($purchaseUpdtate->currencyType),
            date: $purchaseUpdtate->date,
            date_ven: $purchaseUpdtate->date_ven,
            days: $purchaseUpdtate->days,
            observation: $purchaseUpdtate->observation,
            detraccion: $purchaseUpdtate->detraccion,
            fech_detraccion: $purchaseUpdtate->fech_detraccion,
            amount_detraccion: $purchaseUpdtate->amount_detraccion,
            is_detracion: $purchaseUpdtate->is_detracion,
            subtotal: $purchaseUpdtate->subtotal,
            total_desc: $purchaseUpdtate->total_desc,
            inafecto: $purchaseUpdtate->inafecto,
            igv: $purchaseUpdtate->igv,
            total: $purchaseUpdtate->total,
            is_igv: $purchaseUpdtate->is_igv,
            type_document_id: $purchaseUpdtate->document_type_id,
            reference_serie: $purchaseUpdtate->reference_serie,
            reference_correlative: $purchaseUpdtate->reference_correlative,
            saldo: $purchaseUpdtate->saldo,

        );
    }
}
