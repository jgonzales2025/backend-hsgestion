<?php

namespace App\Modules\SaleItemSerial\Infrastructure\Persistence;

use App\Modules\SaleItemSerial\Domain\Entities\SaleItemSerial;
use App\Modules\SaleItemSerial\Domain\Interfaces\SaleItemSerialRepositoryInterface;
use App\Modules\SaleItemSerial\Infrastructure\Models\EloquentSaleItemSerial;

class EloquentSaleItemSerialRepository implements SaleItemSerialRepositoryInterface
{

    public function save(SaleItemSerial $saleItemSerial): SaleItemSerial
    {
        $eloquentSaleItemSerial = EloquentSaleItemSerial::create([
            'sale_id' => $saleItemSerial->getSale()->getId(),
            'article_id' => $saleItemSerial->getArticle()->getArticleId(),
            'serial' => $saleItemSerial->getSerial(),
        ]);

        return new SaleItemSerial(
            id: $eloquentSaleItemSerial->id,
            sale: $saleItemSerial->getSale(),
            article: $saleItemSerial->getArticle(),
            serial: $saleItemSerial->getSerial()
        );
    }
}
