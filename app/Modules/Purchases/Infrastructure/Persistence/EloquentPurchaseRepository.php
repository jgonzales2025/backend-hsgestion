<?php

namespace App\Modules\Purchases\Infrastructure\Persistence;

use App\Modules\Purchases\Domain\Entities\Purchase;
use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;
use App\Modules\Purchases\Infrastructure\Models\EloquentPurchase;

class EloquentPurchaseRepository implements PurchaseRepositoryInterface
{
    public function findAll(): array
    {
        $eloquentpurchase = EloquentPurchase::all();

        return $eloquentpurchase->map(function ($purchase) {
            return new Purchase(
                id: $purchase->id,
                company_id: $purchase->company_id,
                branch_id: $purchase->branch_id,
                supplier_id: $purchase->supplier_id,
                serie: $purchase->serie,
                correlative: $purchase->correlative,
                exchange_type: $purchase->exchange_type,
                methodpayment: $purchase->methodpayment,
                currency: $purchase->currency,
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
        $eloquentpurchase = EloquentPurchase::find($id);

        if (!$eloquentpurchase) {
            return null;
        }
        return new Purchase(
            id: $eloquentpurchase->id,
            company_id: $eloquentpurchase->company_id,
            branch_id: $eloquentpurchase->branch_id,
            supplier_id: $eloquentpurchase->supplier_id,
            serie: $eloquentpurchase->serie,
            correlative: $eloquentpurchase->correlative,
            exchange_type: $eloquentpurchase->exchange_type,
            methodpayment: $eloquentpurchase->methodpayment,
            currency: $eloquentpurchase->currency,
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
            'company_id' => $purchase->getCompanyId(),
            'branch_id' => $purchase->getBranchId(),
            'supplier_id' => $purchase->getSupplierId(),
            'serie' => $purchase->getSerie(),
            'correlative' => $purchase->getCorrelative(),
            'exchange_type' => $purchase->getExchangeType(),
            'methodpayment' => $purchase->getMethodpayment(),
            'currency' => $purchase->getCurrency(),
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
            company_id: $eloquentpurchase->company_id,
            branch_id: $eloquentpurchase->branch_id,
            supplier_id: $eloquentpurchase->supplier_id,
            serie: $eloquentpurchase->serie,
            correlative: $eloquentpurchase->correlative,
            exchange_type: $eloquentpurchase->exchange_type,
            methodpayment: $eloquentpurchase->methodpayment,
            currency: $eloquentpurchase->currency,
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
            'company_id' => $purchase->getCompanyId(),
            'branch_id' => $purchase->getBranchId(),
            'supplier_id' => $purchase->getSupplierId(),
            'serie' => $purchase->getSerie(),
            'correlative' => $purchase->getCorrelative(),
            'exchange_type' => $purchase->getExchangeType(),
            'methodpayment' => $purchase->getMethodpayment(),
            'currency' => $purchase->getCurrency(),
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
            company_id: $purchaseUpdtate->company_id,
            branch_id: $purchaseUpdtate->branch_id,
            supplier_id: $purchaseUpdtate->supplier_id,
            serie: $purchaseUpdtate->serie,
            correlative: $purchaseUpdtate->correlative,
            exchange_type: $purchaseUpdtate->exchange_type,
            methodpayment: $purchaseUpdtate->methodpayment,
            currency: $purchaseUpdtate->currency,
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