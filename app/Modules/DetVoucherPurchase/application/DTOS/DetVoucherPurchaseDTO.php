<?php

namespace App\Modules\DetVoucherPurchase\application\DTOS;

class DetVoucherPurchaseDTO
{
    public int $voucher_id;
    public int $purchase_id;
    public int $amount;
    public function __construct(array $data) {
        $this->voucher_id = $data['voucher_id'] ?? 0;
        $this->purchase_id = $data['purchase_id'];
        $this->amount = $data['amount'];
    }
}