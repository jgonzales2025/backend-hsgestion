<?php

namespace App\Modules\PurchaseItemSerials\Domain\Interface;

use App\Modules\PurchaseItemSerials\Domain\Entities\PurchaseItemSerial;

interface PurchaseItemSerialRepositoryInterface{
   
    public function save(PurchaseItemSerial $purchaseItemSerial):?PurchaseItemSerial;

    public function findById(int $id):array;

    public function deleteByIdPurchaseItemSerial(int $id):void;
}