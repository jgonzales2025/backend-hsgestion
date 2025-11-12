<?php

namespace App\Modules\PurchaseOrderArticle\Infrastructure\Persistence;

use App\Modules\PurchaseOrderArticle\Domain\Entities\PurchaseOrderArticle;
use App\Modules\PurchaseOrderArticle\Domain\Interfaces\PurchaseOrderArticleRepositoryInterface;
use App\Modules\PurchaseOrderArticle\Infrastructure\Models\EloquentPurchaseOrderArticle;

class EloquentPurchaseOrderArticleRepository implements PurchaseOrderArticleRepositoryInterface
{

    public function save(PurchaseOrderArticle $purchaseOrderArticle): PurchaseOrderArticle
    {
        $purchaseOrderArticleEloquent = EloquentPurchaseOrderArticle::create([
            'purchase_order_id' => $purchaseOrderArticle->getPurchaseOrderId(),
            'article_id' => $purchaseOrderArticle->getArticleId(),
            'description' => $purchaseOrderArticle->getDescription(),
            'weight' => $purchaseOrderArticle->getWeight(),
            'quantity' => $purchaseOrderArticle->getQuantity(),
            'purchase_price' => $purchaseOrderArticle->getPurchasePrice(),
            'subtotal' => $purchaseOrderArticle->getSubTotal()
        ]);

        return new PurchaseOrderArticle(
            id: $purchaseOrderArticleEloquent->id,
            purchase_order_id: $purchaseOrderArticleEloquent->purchase_order_id,
            article_id: $purchaseOrderArticleEloquent->article_id,
            description: $purchaseOrderArticleEloquent->description,
            weight: $purchaseOrderArticleEloquent->weight,
            quantity: $purchaseOrderArticleEloquent->quantity,
            purchase_price: $purchaseOrderArticleEloquent->purchase_price,
            subtotal: $purchaseOrderArticleEloquent->subtotal,
            cod_fab: $purchaseOrderArticleEloquent->article->cod_fab
        );
    }

    public function findByPurchaseOrderId(int $purchaseOrderId): array
    {
        $articles = EloquentPurchaseOrderArticle::where('purchase_order_id', $purchaseOrderId)->get();

        return $articles->map(function ($article) {
            return new PurchaseOrderArticle(
                id: $article->id,
                purchase_order_id: $article->purchase_order_id,
                article_id: $article->article_id,
                description: $article->description,
                weight: $article->weight,
                quantity: $article->quantity,
                purchase_price: $article->purchase_price,
                subtotal: $article->subtotal,
                cod_fab: $article->article->cod_fab
            );
        })->toArray();
    }

    public function deleteByPurchaseOrderId(int $purchaseOrderId): void
    {
        EloquentPurchaseOrderArticle::where('purchase_order_id', $purchaseOrderId)->delete();
    }
}
