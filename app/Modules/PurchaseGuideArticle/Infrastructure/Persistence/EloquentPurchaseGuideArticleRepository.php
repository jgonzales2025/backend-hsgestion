<?php

namespace App\Modules\PurchaseGuideArticle\Infrastructure\Persistence;

use App\Modules\PurchaseGuideArticle\Domain\Entities\PurchaseGuideArticle;
use App\Modules\PurchaseGuideArticle\Domain\Interface\PurchaseGuideArticleRepositoryInterface;
use App\Modules\PurchaseGuideArticle\Infrastructure\Models\EloquentPurchaseGuideArticle;


class EloquentPurchaseGuideArticleRepository implements PurchaseGuideArticleRepositoryInterface
{

    public function save(PurchaseGuideArticle $purchaseGuideArticle): ?PurchaseGuideArticle
    {
        $purchaseGuideArticle = EloquentPurchaseGuideArticle::create([

            'purchase_guide_id' => $purchaseGuideArticle->getPurchaseGuideId(),
            'article_id' => $purchaseGuideArticle->getArticleId(),
            'description' => $purchaseGuideArticle->getDescription(),
            'quantity' => $purchaseGuideArticle->getQuantity(),

        ]);
        return new PurchaseGuideArticle(
            id: $purchaseGuideArticle->id,
            purchase_guide_id: $purchaseGuideArticle->purchase_guide_id,
            article_id: $purchaseGuideArticle->article_id,
            description: $purchaseGuideArticle->description,
            quantity: $purchaseGuideArticle->quantity
        );
    }
    public function findAll(): array
    {
        return [];
    }
    public function findById(int $id): array
    {
            $eloquentPurchaseGuideArticle = EloquentPurchaseGuideArticle::where('purchase_guide_id', $id)->get();
        return $eloquentPurchaseGuideArticle->map(function ($purchaseGuideArticle) {
            return new PurchaseGuideArticle(
                id: $purchaseGuideArticle->id,
                purchase_guide_id: $purchaseGuideArticle->purchase_guide_id,
                article_id: $purchaseGuideArticle->article_id,
                description: $purchaseGuideArticle->description,
                quantity: $purchaseGuideArticle->quantity
            );
        })->toArray();
    }

   public function deleteByPurchaseGuideId(int $id): void
    {
        EloquentPurchaseGuideArticle::where('purchase_guide_id', $id)->delete();
    }
}