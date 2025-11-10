<?php

namespace App\Modules\PurchaseItemSerials\Application\UseCases;

use App\Modules\PurchaseItemSerials\Application\DTOS\PurchaseItemSerialDTO;
use App\Modules\PurchaseItemSerials\Domain\Entities\PurchaseItemSerial;
use App\Modules\PurchaseItemSerials\Domain\Interface\PurchaseItemSerialRepositoryInterface;

class CreatePurchaseItemSerialUseCase{
    public function __construct(
        private readonly PurchaseItemSerialRepositoryInterface $purchaseItemSerialRepositoryInterface
    ){}

    public function execute(PurchaseItemSerialDTO $purchaseItemSerial):?PurchaseItemSerial{
        $PurchaseItemSerial = new PurchaseItemSerial(
            id:null,
            purchase_guide_id:$purchaseItemSerial->purchase_guide_id,
            article_id:$purchaseItemSerial->article_id,
            serial:$purchaseItemSerial->serial,
        );
        return $this->purchaseItemSerialRepositoryInterface->save($PurchaseItemSerial);
    }
}