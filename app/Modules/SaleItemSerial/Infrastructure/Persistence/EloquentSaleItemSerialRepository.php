<?php

namespace App\Modules\SaleItemSerial\Infrastructure\Persistence;

use App\Modules\EntryItemSerial\Infrastructure\Models\EloquentEntryItemSerial;
use App\Modules\SaleItemSerial\Domain\Entities\SaleItemSerial;
use App\Modules\SaleItemSerial\Domain\Interfaces\SaleItemSerialRepositoryInterface;
use App\Modules\SaleItemSerial\Infrastructure\Models\EloquentSaleItemSerial;

class EloquentSaleItemSerialRepository implements SaleItemSerialRepositoryInterface
{

    public function save(SaleItemSerial $saleItemSerial): SaleItemSerial
    {
        $eloquentSaleItemSerial = EloquentSaleItemSerial::create([
            'sale_id' => $saleItemSerial->getSale()->getId(),
            'article_id' => $saleItemSerial->getArticle()->getArticle()->getId(),
            'serial' => $saleItemSerial->getSerial(),
        ]);

        $eloquentEntryItemSerial = EloquentEntryItemSerial::where('serial', $saleItemSerial->getSerial())->first();
        $eloquentEntryItemSerial->status = 0;
        $eloquentEntryItemSerial->save();

        return new SaleItemSerial(
            id: $eloquentSaleItemSerial->id,
            sale: $saleItemSerial->getSale(),
            article: $saleItemSerial->getArticle(),
            serial: $saleItemSerial->getSerial()
        );
    }

    public function findSerialsBySaleId(int $saleId): array
    {
        $rows = EloquentSaleItemSerial::where('sale_id', $saleId)->get(['article_id', 'serial']);
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row->article_id][] = $row->serial;
        }
        return $grouped;
    }

    public function deleteSerialsBySaleId(int $saleId): void
    {
        $serials = $this->findSerialsBySaleId($saleId);
        $allSerials = array_merge(...array_values($serials));
        
        EloquentEntryItemSerial::whereIn('serial', $allSerials)
            ->update(['status' => 1]);

        EloquentSaleItemSerial::where('sale_id', $saleId)->delete();
    }
}
