<?php

namespace App\Modules\PurchaseOrderArticle\Application\UseCases;

use App\Modules\PurchaseOrderArticle\Domain\Interfaces\PurchaseOrderArticleRepositoryInterface;

readonly class FindPurchaseOrderIdUseCase
{
    public function __construct(private readonly PurchaseOrderArticleRepositoryInterface $purchaseOrderArticleRepository){}

    public function execute(int $purchaseOrderId): array
    {
        return $this->purchaseOrderArticleRepository->findByPurchaseOrderId($purchaseOrderId);
    }
}
