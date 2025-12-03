<?php

namespace App\Modules\DetEntryguidePurchaseOrder\Domain\Entities;

class DetEntryguidePurchaseOrder
{
    private  ?int $id;
    private int $purchase_order_id;
    private int $entry_guide_id;
    public function __construct(
        ?int $id,
        int $purchase_order_id,
        int $entry_guide_id
    ) {
        $this->id = $id;
        $this->purchase_order_id = $purchase_order_id;
        $this->entry_guide_id = $entry_guide_id;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getPurchaseOrderId(): int
    {
        return $this->purchase_order_id;
    }
    public function getEntryGuideId(): int
    {
        return $this->entry_guide_id;
    }
}
