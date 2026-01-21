<?php

namespace App\Modules\SaleItemSerial\Domain\Interfaces;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\SaleItemSerial\Domain\Entities\SaleItemSerial;

interface SaleItemSerialRepositoryInterface
{
    public function save(SaleItemSerial $saleItemSerial): SaleItemSerial;
    public function findSerialsBySaleId(int $saleId): array;
    public function deleteSerialsBySaleId(int $saleId): void;
    public function findSaleBySerial(string $serial): ?Sale;
    public function findArticleBySerial(string $serial): ?Article;
    public function findSerialBySaleAndArticle(int $saleId, int $articleId): ?array;
    public function updateStatusBySerials(array $serials): void;
    public function findSerialsInactiveBySaleId(int $saleId): array;
}
