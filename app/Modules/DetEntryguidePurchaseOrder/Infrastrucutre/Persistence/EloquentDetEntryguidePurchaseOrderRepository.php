<?php

namespace App\Modules\DetEntryguidePurchaseOrder\Infrastrucutre\Persistence;

use App\Modules\DetEntryguidePurchaseOrder\Domain\Entities\DetEntryguidePurchaseOrder;
use App\Modules\DetEntryguidePurchaseOrder\Domain\Interface\DetEntryguidePurchaseOrderRepositoryInterface;
use App\Modules\DetEntryguidePurchaseOrder\Infrastrucutre\Models\EloquentDetEntryguidePurchaseOrder;

class EloquentDetEntryguidePurchaseOrderRepository implements DetEntryguidePurchaseOrderRepositoryInterface{

    public function create(DetEntryguidePurchaseOrder $detEntryguidePurchaseOrder): DetEntryguidePurchaseOrder
    {
        $eloquentDetEntryguidePurchaseOrder = EloquentDetEntryguidePurchaseOrder::create([
            'purchase_order_id' => $detEntryguidePurchaseOrder->getPurchaseOrderId(),
            'entry_guide_id' => $detEntryguidePurchaseOrder->getEntryGuideId()
        ]);
        return new DetEntryguidePurchaseOrder(
            id: $eloquentDetEntryguidePurchaseOrder->id,
            purchase_order_id: $eloquentDetEntryguidePurchaseOrder->purchase_order_id,
            entry_guide_id: $eloquentDetEntryguidePurchaseOrder->entry_guide_id
        );
    }

    public function update(DetEntryguidePurchaseOrder $detEntryguidePurchaseOrder): DetEntryguidePurchaseOrder
    {
        $eloquentDetEntryguidePurchaseOrder = EloquentDetEntryguidePurchaseOrder::find($detEntryguidePurchaseOrder->getId());
        $eloquentDetEntryguidePurchaseOrder->purchase_order_id = $detEntryguidePurchaseOrder->getPurchaseOrderId();
        $eloquentDetEntryguidePurchaseOrder->entry_guide_id = $detEntryguidePurchaseOrder->getEntryGuideId();
        $eloquentDetEntryguidePurchaseOrder->save();
        return new DetEntryguidePurchaseOrder(
            id: $eloquentDetEntryguidePurchaseOrder->id,
            purchase_order_id: $eloquentDetEntryguidePurchaseOrder->purchase_order_id,
            entry_guide_id: $eloquentDetEntryguidePurchaseOrder->entry_guide_id
        );
    }

    public function findById(int $id): DetEntryguidePurchaseOrder
    {
        $eloquentDetEntryguidePurchaseOrder = EloquentDetEntryguidePurchaseOrder::find($id);
        return new DetEntryguidePurchaseOrder(
            id: $eloquentDetEntryguidePurchaseOrder->id,
            purchase_order_id: $eloquentDetEntryguidePurchaseOrder->purchase_order_id,
            entry_guide_id: $eloquentDetEntryguidePurchaseOrder->entry_guide_id
        );
    }

    public function findByIdEntryGuide(int $id): array
    {
        $eloquentDetEntryguidePurchaseOrder = EloquentDetEntryguidePurchaseOrder::where('entry_guide_id', $id)->get();

        $arraydet = $eloquentDetEntryguidePurchaseOrder->map(function ($eloquentDetEntryguidePurchaseOrder) {
            return new DetEntryguidePurchaseOrder(
                id: $eloquentDetEntryguidePurchaseOrder->id,
                purchase_order_id: $eloquentDetEntryguidePurchaseOrder->purchase_order_id,
                entry_guide_id: $eloquentDetEntryguidePurchaseOrder->entry_guide_id
            );
        })->toArray();
        return $arraydet;
    }

    public function findAll(): array
    {
        $eloquentDetEntryguidePurchaseOrders = EloquentDetEntryguidePurchaseOrder::all();
        return $eloquentDetEntryguidePurchaseOrders->map(function ($eloquentDetEntryguidePurchaseOrder) {
            return new DetEntryguidePurchaseOrder(
                id: $eloquentDetEntryguidePurchaseOrder->id,
                purchase_order_id: $eloquentDetEntryguidePurchaseOrder->purchase_order_id,
                entry_guide_id: $eloquentDetEntryguidePurchaseOrder->entry_guide_id
            );
        })->toArray();
    }

}