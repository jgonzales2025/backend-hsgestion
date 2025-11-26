<?php

namespace App\Modules\PurchaseOrder\Infrastructure\Persistence;

use App\Modules\PurchaseOrder\Domain\Entities\PurchaseOrder;
use App\Modules\PurchaseOrder\Domain\Interfaces\PurchaseOrderRepositoryInterface;
use App\Modules\PurchaseOrder\Infrastructure\Models\EloquentPurchaseOrder;

class EloquentPurchaseOrderRepository implements PurchaseOrderRepositoryInterface
{

    public function findAll(string $role, array $branches, int $companyId): array
    {
        if ($role === 'admin') {
            $purchaseOrders = EloquentPurchaseOrder::orderBy('created_at', 'desc')->get();
        } else {
            $purchaseOrders = EloquentPurchaseOrder::where('company_id', $companyId)
                ->whereIn('branch_id', $branches)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return $purchaseOrders->map(function ($purchaseOrder) {
            return new PurchaseOrder(
                id: $purchaseOrder->id,
                company_id: $purchaseOrder->company_id,
                branch: $purchaseOrder->branch->toDomain($purchaseOrder->branch),
                serie: $purchaseOrder->serie,
                correlative: $purchaseOrder->correlative,
                date: $purchaseOrder->date,
                delivery_date: $purchaseOrder->delivery_date,
                due_date: $purchaseOrder->due_date,
                days: $purchaseOrder->days,
                contact_name: $purchaseOrder->contact_name,
                contact_phone: $purchaseOrder->contact_phone,
                currencyType: $purchaseOrder->currencyType->toDomain($purchaseOrder->currencyType),
                parallel_rate: $purchaseOrder->parallel_rate,
                paymentType: $purchaseOrder->paymentType->toDomain($purchaseOrder->paymentType),
                order_number_supplier: $purchaseOrder->order_number_supplier,
                observations: $purchaseOrder->observations,
                supplier: $purchaseOrder->supplier->toDomain($purchaseOrder->supplier),
                status: $purchaseOrder->status,
                percentage_igv: $purchaseOrder->percentage_igv,
                is_igv_included: $purchaseOrder->is_igv_included,
                subtotal: $purchaseOrder->subtotal,
                igv: $purchaseOrder->igv,
                total: $purchaseOrder->total
            );
        })->toArray();
    }

    public function save(PurchaseOrder $purchaseOrder): ?PurchaseOrder
    {
        $purchaseOrderEloquent = EloquentPurchaseOrder::create([
            'company_id' => $purchaseOrder->getCompanyId(),
            'branch_id' => $purchaseOrder->getBranch()->getId(),
            'serie' => $purchaseOrder->getSerie(),
            'correlative' => $purchaseOrder->getCorrelative(),
            'date' => $purchaseOrder->getDate(),
            'delivery_date' => $purchaseOrder->getDeliveryDate(),
            'due_date' => $purchaseOrder->getDueDate(),
            'days' => $purchaseOrder->getDays(),
            'contact_name' => $purchaseOrder->getContactName(),
            'contact_phone' => $purchaseOrder->getContactPhone(),
            'currency_type_id' => $purchaseOrder->getCurrencyType()->getId(),
            'parallel_rate' => $purchaseOrder->getParallelRate(),
            'payment_type_id' => $purchaseOrder->getPaymentType()->getId(),
            'order_number_supplier' => $purchaseOrder->getOrderNumberSupplier(),
            'observations' => $purchaseOrder->getObservations(),
            'supplier_id' => $purchaseOrder->getSupplier()->getId(),
            'percentage_igv' => $purchaseOrder->getPercentageIgv(),
            'is_igv_included' => $purchaseOrder->getIsIgvIncluded(),
            'subtotal' => $purchaseOrder->getSubtotal(),
            'igv' => $purchaseOrder->getIgv(),
            'total' => $purchaseOrder->getTotal()
        ]);
        $purchaseOrderEloquent->refresh();

        return new PurchaseOrder(
            id: $purchaseOrderEloquent->id,
            company_id: $purchaseOrderEloquent->company_id,
            branch: $purchaseOrderEloquent->branch->toDomain($purchaseOrderEloquent->branch),
            serie: $purchaseOrderEloquent->serie,
            correlative: $purchaseOrderEloquent->correlative,
            date: $purchaseOrderEloquent->date,
            delivery_date: $purchaseOrderEloquent->delivery_date,
            due_date: $purchaseOrderEloquent->due_date,
            days: $purchaseOrderEloquent->days,
            contact_name: $purchaseOrderEloquent->contact_name,
            contact_phone: $purchaseOrderEloquent->contact_phone,
            currencyType: $purchaseOrderEloquent->currencyType->toDomain($purchaseOrderEloquent->currencyType),
            parallel_rate: $purchaseOrderEloquent->parallel_rate,
            paymentType: $purchaseOrderEloquent->paymentType->toDomain($purchaseOrderEloquent->paymentType),
            order_number_supplier: $purchaseOrderEloquent->order_number_supplier,
            observations: $purchaseOrderEloquent->observations,
            supplier: $purchaseOrderEloquent->supplier->toDomain($purchaseOrderEloquent->supplier),
            status: $purchaseOrderEloquent->status,
            percentage_igv: $purchaseOrderEloquent->percentage_igv,
            is_igv_included: $purchaseOrderEloquent->is_igv_included,
            subtotal: $purchaseOrderEloquent->subtotal,
            igv: $purchaseOrderEloquent->igv,
            total: $purchaseOrderEloquent->total
        );
    }

    public function getLastDocumentNumber(string $serie): ?string
    {
        $purchaseOder = EloquentPurchaseOrder::where('serie', $serie)
            ->orderBy('correlative', 'desc')
            ->first();

        return $purchaseOder?->correlative;
    }

    public function findById(int $id): ?PurchaseOrder
    {
        $purchaseOrderEloquent = EloquentPurchaseOrder::find($id);

        if (!$purchaseOrderEloquent) {
            return null;
        }

        return new PurchaseOrder(
            id: $purchaseOrderEloquent->id,
            company_id: $purchaseOrderEloquent->company_id,
            branch: $purchaseOrderEloquent->branch->toDomain($purchaseOrderEloquent->branch),
            serie: $purchaseOrderEloquent->serie,
            correlative: $purchaseOrderEloquent->correlative,
            date: $purchaseOrderEloquent->date,
            delivery_date: $purchaseOrderEloquent->delivery_date,
            due_date: $purchaseOrderEloquent->due_date,
            days: $purchaseOrderEloquent->days,
            contact_name: $purchaseOrderEloquent->contact_name,
            contact_phone: $purchaseOrderEloquent->contact_phone,
            currencyType: $purchaseOrderEloquent->currencyType->toDomain($purchaseOrderEloquent->currencyType),
            parallel_rate: $purchaseOrderEloquent->parallel_rate,
            paymentType: $purchaseOrderEloquent->paymentType->toDomain($purchaseOrderEloquent->paymentType),
            order_number_supplier: $purchaseOrderEloquent->order_number_supplier,
            observations: $purchaseOrderEloquent->observations,
            supplier: $purchaseOrderEloquent->supplier->toDomain($purchaseOrderEloquent->supplier),
            status: $purchaseOrderEloquent->status,
            percentage_igv: $purchaseOrderEloquent->percentage_igv,
            is_igv_included: $purchaseOrderEloquent->is_igv_included,
            subtotal: $purchaseOrderEloquent->subtotal,
            igv: $purchaseOrderEloquent->igv,
            total: $purchaseOrderEloquent->total
        );
    }

    public function update(PurchaseOrder $purchaseOrder): ?PurchaseOrder
    {
        $purchaseOrderEloquent = EloquentPurchaseOrder::find($purchaseOrder->getId());

        if (!$purchaseOrderEloquent) {
            return null;
        }
        
        $purchaseOrderEloquent->update([
            'company_id' => $purchaseOrder->getCompanyId(),
            'branch_id' => $purchaseOrder->getBranch()->getId(),
            'serie' => $purchaseOrder->getSerie(),
            'date' => $purchaseOrder->getDate(),
            'delivery_date' => $purchaseOrder->getDeliveryDate(),
            'due_date' => $purchaseOrder->getDueDate(),
            'days' => $purchaseOrder->getDays(),
            'contact_name' => $purchaseOrder->getContactName(),
            'contact_phone' => $purchaseOrder->getContactPhone(),
            'currency_type_id' => $purchaseOrder->getCurrencyType()->getId(),
            'parallel_rate' => $purchaseOrder->getParallelRate(),
            'payment_type_id' => $purchaseOrder->getPaymentType()->getId(),
            'order_number_supplier' => $purchaseOrder->getOrderNumberSupplier(),
            'observations' => $purchaseOrder->getObservations(),
            'supplier_id' => $purchaseOrder->getSupplier()->getId(),
            'status' => $purchaseOrder->getStatus(),
            'percentage_igv' => $purchaseOrder->getPercentageIgv(),
            'is_igv_included' => $purchaseOrder->getIsIgvIncluded(),
            'subtotal' => $purchaseOrder->getSubtotal(),
            'igv' => $purchaseOrder->getIgv(),
            'total' => $purchaseOrder->getTotal()
        ]);
        $purchaseOrderEloquent->refresh();
              
        return new PurchaseOrder(
            id: $purchaseOrderEloquent->id,
            company_id: $purchaseOrderEloquent->company_id,
            branch: $purchaseOrderEloquent->branch->toDomain($purchaseOrderEloquent->branch),
            serie: $purchaseOrderEloquent->serie,
            correlative: $purchaseOrderEloquent->correlative,
            date: $purchaseOrderEloquent->date,
            delivery_date: $purchaseOrderEloquent->delivery_date,
            due_date: $purchaseOrderEloquent->due_date,
            days: $purchaseOrderEloquent->days,
            contact_name: $purchaseOrderEloquent->contact_name,
            contact_phone: $purchaseOrderEloquent->contact_phone,
            currencyType: $purchaseOrderEloquent->currencyType->toDomain($purchaseOrderEloquent->currencyType),
            parallel_rate: $purchaseOrderEloquent->parallel_rate,
            paymentType: $purchaseOrderEloquent->paymentType->toDomain($purchaseOrderEloquent->paymentType),
            order_number_supplier: $purchaseOrderEloquent->order_number_supplier,
            observations: $purchaseOrderEloquent->observations,
            supplier: $purchaseOrderEloquent->supplier?->toDomain($purchaseOrderEloquent->supplier),
            status: $purchaseOrderEloquent->status,
            percentage_igv: $purchaseOrderEloquent->percentage_igv,
            is_igv_included: $purchaseOrderEloquent->is_igv_included,
            subtotal: $purchaseOrderEloquent->subtotal,
            igv: $purchaseOrderEloquent->igv,
            total: $purchaseOrderEloquent->total
        );
    }
}
