<?php


namespace App\Modules\DetEntryguidePurchaseorder\application\DTOS;

class DetEntryguidePurchaseorderDTO
{
    public int $purchase_order_id;
    public int $entry_guide_id;
    public function __construct(array $data) {
        $this->purchase_order_id = $data['purchase_order_id'];
        $this->entry_guide_id = $data['entry_guide_id'];
    }
}