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
                branch_id: $purchaseOrder->branch_id,
                serie: $purchaseOrder->serie,
                correlative: $purchaseOrder->correlative,
                date: $purchaseOrder->date,
                delivery_date: $purchaseOrder->delivery_date,
                contact: $purchaseOrder->contact,
                order_number_supplier: $purchaseOrder->order_number_supplier,
                supplier: $purchaseOrder->supplier->toDomain($purchaseOrder->supplier),
                status: $purchaseOrder->status
            );
        })->toArray();
    }

    public function save(PurchaseOrder $purchaseOrder): ?PurchaseOrder
    {
        $purchaseOrderEloquent = EloquentPurchaseOrder::create([
            'company_id' => $purchaseOrder->getCompanyId(),
            'branch_id' => $purchaseOrder->getBranchId(),
            'serie' => $purchaseOrder->getSerie(),
            'correlative' => $purchaseOrder->getCorrelative(),
            'date' => $purchaseOrder->getDate(),
            'delivery_date' => $purchaseOrder->getDeliveryDate(),
            'contact' => $purchaseOrder->getContact(),
            'order_number_supplier' => $purchaseOrder->getOrderNumberSupplier(),
            'supplier_id' => $purchaseOrder->getSupplier()->getId()
        ]);

        return new PurchaseOrder(
            id: $purchaseOrderEloquent->id,
            company_id: $purchaseOrderEloquent->company_id,
            branch_id: $purchaseOrderEloquent->branch_id,
            serie: $purchaseOrderEloquent->serie,
            correlative: $purchaseOrderEloquent->correlative,
            date: $purchaseOrderEloquent->date,
            delivery_date: $purchaseOrderEloquent->delivery_date,
            contact: $purchaseOrderEloquent->contact,
            order_number_supplier: $purchaseOrderEloquent->order_number_supplier,
            supplier: $purchaseOrderEloquent->supplier?->toDomain($purchaseOrderEloquent->supplier),
            status: $purchaseOrderEloquent->status
        );
    }

    public function getLastDocumentNumber(string $serie): ?string
    {
        $purchaseOder = EloquentPurchaseOrder::where('serie', $serie)
            ->orderBy('correlative', 'desc')
            ->first();

        return $purchaseOder?->document_number;
    }
}
