<?php

namespace App\Modules\Purchases\Infrastructure\Persistence;

use App\Modules\Purchases\Domain\Entities\Purchase;
use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;
use App\Modules\Purchases\Infrastructure\Models\EloquentPurchase;

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
                total: $purchase->total

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
            total: $eloquentpurchase->total
        );
    }
    public function save(Purchase $purchase): ?Purchase
    {
        $eloquentpurchase = EloquentPurchase::create([
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
            'total' => $purchase->getTotal()
        ]);
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
            total: $eloquentpurchase->total
        );
    }
    public function update(Purchase $purchase): ?Purchase
    {
        $purchaseUpdtate = EloquentPurchase::find($purchase->getId());
        if (!$purchaseUpdtate) {
            return null;
        }

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
            'total' => $purchase->getTotal()
        ]);
        return new Purchase(
            id: $purchaseUpdtate->id,
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
            total: $purchaseUpdtate->total
        );
    }
}
