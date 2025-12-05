<?php

namespace App\Modules\DetVoucherPurchase\Domain\Entities;

class DetVoucherPurchase
{
    public int $id;
    public int $voucher_id;
    public int $purchase_id;
    public int $amount;
    public function __construct(
        int $id,
        int $voucher_id,
        int $purchase_id,
        int $amount,
    ) {
        $this->id = $id;
        $this->voucher_id = $voucher_id;
        $this->purchase_id = $purchase_id;
        $this->amount = $amount;
    }
    public function getId(){return $this->id;}
    public function getVoucherId(){return $this->voucher_id;}
    public function getPurchaseId(){return $this->purchase_id;}
    public function getAmount(){return $this->amount;}
}
