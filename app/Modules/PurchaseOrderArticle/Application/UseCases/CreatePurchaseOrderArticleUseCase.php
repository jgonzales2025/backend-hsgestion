<?php

namespace App\Modules\PurchaseOrderArticle\Application\UseCases;

use App\Modules\PurchaseOrderArticle\Application\DTOs\PurchaseOrderArticleDTO;
use App\Modules\PurchaseOrderArticle\Domain\Entities\PurchaseOrderArticle;
use App\Modules\PurchaseOrderArticle\Domain\Interfaces\PurchaseOrderArticleRepositoryInterface;

readonly class CreatePurchaseOrderArticleUseCase
{
    public function __construct(private readonly PurchaseOrderArticleRepositoryInterface $repository){}

    public function execute(PurchaseOrderArticleDTO $purchaseOrderArticleDTO): ?PurchaseOrderArticle
    {
        $purchaseOrderArticle = new PurchaseOrderArticle(
            id: 0,
            purchase_order_id: $purchaseOrderArticleDTO->purchase_order_id,
            article_id: $purchaseOrderArticleDTO->article_id,
            description: $purchaseOrderArticleDTO->description,
            weight: $purchaseOrderArticleDTO->weight,
            quantity: $purchaseOrderArticleDTO->quantity,
            purchase_price: $purchaseOrderArticleDTO->purchase_price,
            subtotal: $purchaseOrderArticleDTO->subtotal
        );

        return $this->repository->save($purchaseOrderArticle);
    }
}
