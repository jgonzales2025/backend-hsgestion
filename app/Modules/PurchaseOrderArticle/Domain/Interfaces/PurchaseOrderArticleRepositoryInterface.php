<?php

namespace App\Modules\PurchaseOrderArticle\Domain\Interfaces;

use App\Modules\PurchaseOrderArticle\Domain\Entities\PurchaseOrderArticle;

interface PurchaseOrderArticleRepositoryInterface
{
    public function save(PurchaseOrderArticle $purchaseOrderArticle): PurchaseOrderArticle;
    public function findByPurchaseOrderId(int $purchaseOrderId): array;
}
