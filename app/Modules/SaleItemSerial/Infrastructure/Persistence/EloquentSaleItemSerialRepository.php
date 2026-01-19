<?php

namespace App\Modules\SaleItemSerial\Infrastructure\Persistence;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\EntryItemSerial\Infrastructure\Models\EloquentEntryItemSerial;
use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\SaleItemSerial\Domain\Entities\SaleItemSerial;
use App\Modules\SaleItemSerial\Domain\Interfaces\SaleItemSerialRepositoryInterface;
use App\Modules\SaleItemSerial\Infrastructure\Models\EloquentSaleItemSerial;

class EloquentSaleItemSerialRepository implements SaleItemSerialRepositoryInterface
{

    public function __construct(
        private SaleRepositoryInterface $saleRepository,
        private ArticleRepositoryInterface $articleRepository
    ) {
    }

    public function save(SaleItemSerial $saleItemSerial): SaleItemSerial
    {
        $eloquentSaleItemSerial = EloquentSaleItemSerial::create([
            'sale_id' => $saleItemSerial->getSale()->getId(),
            'article_id' => $saleItemSerial->getArticle()->getArticle()->getId(),
            'serial' => $saleItemSerial->getSerial(),
        ]);

        $eloquentEntryItemSerial = EloquentEntryItemSerial::where('serial', $saleItemSerial->getSerial())->first();
        if ($eloquentEntryItemSerial) {
            $eloquentEntryItemSerial->status = 0;
            $eloquentEntryItemSerial->save();
        }

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

    public function findSaleBySerial(string $serial): Sale|null
    {
        $saleItemSerial = EloquentSaleItemSerial::where('serial', $serial)->first();

        if (!$saleItemSerial) {
            return null;
        }

        return $this->saleRepository->findById($saleItemSerial->sale_id);
    }

    public function findArticleBySerial(string $serial): Article|null
    {
        $saleItemSerial = EloquentSaleItemSerial::where('serial', $serial)->first();

        if (!$saleItemSerial) {
            return null;
        }

        return $this->articleRepository->findById($saleItemSerial->article_id);
    }
}
