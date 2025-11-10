<?php

namespace App\Modules\PurchaseOrderArticle\Application\UseCases;

use App\Modules\PurchaseOrderArticle\Domain\Interfaces\PurchaseOrderArticleRepositoryInterface;

readonly class DeleteByPurchaseOrderIdUseCase
{
    public function __construct(private readonly PurchaseOrderArticleRepositoryInterface $purchaseOrderArticleRepository){}

    public function execute(int $purchaseOrderId): void
    {
        $this->purchaseOrderArticleRepository->deleteByPurchaseOrderId($purchaseOrderId);
    }
}
