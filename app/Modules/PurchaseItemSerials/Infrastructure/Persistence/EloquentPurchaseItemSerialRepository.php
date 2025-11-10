<?php

namespace App\Modules\PurchaseItemSerials\Infrastructure\Persistence;

use App\Modules\PurchaseItemSerials\Domain\Entities\PurchaseItemSerial;
use App\Modules\PurchaseItemSerials\Domain\Interface\PurchaseItemSerialRepositoryInterface;
use App\Modules\PurchaseItemSerials\Infrastructure\Models\EloquentPurchaseItemSerial;

class EloquentPurchaseItemSerialRepository implements PurchaseItemSerialRepositoryInterface{
     
    public function save(PurchaseItemSerial $purchaseItemSerial):?PurchaseItemSerial{
         $eloquentPurchaseItemSerial = EloquentPurchaseItemSerial::create([
            'purchase_guide_id' => $purchaseItemSerial->getPurchaseGuideId(),
            'article_id' => $purchaseItemSerial->getArticleId(),
            'serial' => $purchaseItemSerial->getSerial(),
        ]);
        return new PurchaseItemSerial(
            id: $eloquentPurchaseItemSerial->id,
            purchase_guide_id: $eloquentPurchaseItemSerial->purchase_guide_id,
            article_id: $eloquentPurchaseItemSerial->article_id,
            serial: $eloquentPurchaseItemSerial->serial,
        );
    }

    public function findById(int $id):array{
        $eloquentFindById = EloquentPurchaseItemSerial::where('purchase_guide_id',$id);
        if (!$eloquentFindById) {
            return [];
        }
      return  $eloquentFindById->map(function ($purchaseItemSerial) {
        return new PurchaseItemSerial(
            id: $purchaseItemSerial->id,
            purchase_guide_id: $purchaseItemSerial->purchase_guide_id,
            article_id: $purchaseItemSerial->article_id,
            serial: $purchaseItemSerial->serial,
        );
    })->toArray();
    }

    public function deleteByIdPurchaseItemSerial(int $id):void{
        EloquentPurchaseItemSerial::where('purchase_guide_id',$id)->delete();
    
    }

}