<?php

namespace App\Modules\DispatchArticle\Infrastructure\Persistence;

use App\Modules\DispatchArticle\Domain\Entities\DispatchArticle;
use App\Modules\DispatchArticle\Domain\Interface\DispatchArticleRepositoryInterface;
use App\Modules\DispatchArticle\Infrastructure\Models\EloquentDispatchArticle;

class EloquentDispatchArticleRepository implements DispatchArticleRepositoryInterface
{
    public function findAll(): array
    {
        $dispatchArticle = EloquentDispatchArticle::all();

        return $dispatchArticle->map(function ($dispatchArticleData) {
            return new DispatchArticle(
                id: $dispatchArticleData->id,
                dispatch_id: $dispatchArticleData->dispatch_id,
                article_id: $dispatchArticleData->article_id,
                quantity: $dispatchArticleData->quantity,
                weight: $dispatchArticleData->weight,
                saldo: $dispatchArticleData->saldo,
                name: $dispatchArticleData->name,
                subtotal_weight: $dispatchArticleData->subtotal_weight
            );
        })->toArray();
    }
    public function save(DispatchArticle $dispatchArticle): ?DispatchArticle
    {
        $dispatchArticle = EloquentDispatchArticle::create(
            [
                'id' => $dispatchArticle->getId(),
                'dispatch_id' => $dispatchArticle->getDispatchID(),
                'article_id' => $dispatchArticle->getArticleID(),
                'quantity' => $dispatchArticle->getQuantity(),
                'weight' => $dispatchArticle->getWeight(),
                'saldo' => $dispatchArticle->getSaldo(),
                'name' => $dispatchArticle->getName(),
                'subtotal_weight' => $dispatchArticle->getsubTotalWeight()
            ]
        );

        return new DispatchArticle(
            id: $dispatchArticle->id,
            dispatch_id: $dispatchArticle->dispatch_id,
            article_id: $dispatchArticle->article_id,
            quantity: $dispatchArticle->quantity,
            weight: $dispatchArticle->weight,
            saldo: $dispatchArticle->saldo,
            name: $dispatchArticle->name,
            subtotal_weight: $dispatchArticle->subtotal_weight
        );
    }
    public function findById(int $id): array
    {

        $eloquentSaleArticles = EloquentDispatchArticle::where('dispatch_id', $id)->get();

        return $eloquentSaleArticles->map(function ($dispatchArticle) {
            return new DispatchArticle(
                id: $dispatchArticle->id,
                dispatch_id: $dispatchArticle->dispatch_id,
                article_id: $dispatchArticle->article_id,
                quantity: $dispatchArticle->quantity,
                weight: $dispatchArticle->weight,
                saldo: $dispatchArticle->saldo,
                name: $dispatchArticle->name,
                subtotal_weight: $dispatchArticle->subtotal_weight
            );
        })->toArray();
    }
    public function deleteBySaleId(int $id): void
    {
        EloquentDispatchArticle::where('dispatch_id', $id)->delete();
    }
}