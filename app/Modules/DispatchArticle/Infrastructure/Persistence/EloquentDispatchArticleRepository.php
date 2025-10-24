<?php

namespace App\Modules\DispatchArticle\Infrastructure\Persistence;

use App\Modules\DispatchArticle\Domain\Entities\DispatchArticle;
use App\Modules\DispatchArticle\Domain\Interface\DispatchArticleRepositoryInterface;
use App\Modules\DispatchArticle\Infrastructure\Models\EloquentDispatchArticle;

class EloquentDispatchArticleRepository implements DispatchArticleRepositoryInterface{
    public function findAll(): array{
        $dispatchArticle = EloquentDispatchArticle::all();

        return $dispatchArticle->map(function($dispatchArticleData){
           return new DispatchArticle(
                 id:$dispatchArticleData->id,
                 dispatch_id:$dispatchArticleData->dispatch_id,
                 article_id:$dispatchArticleData->article_id,
                 quantity:$dispatchArticleData->quantity,
                 weight:$dispatchArticleData->weight,
                 saldo:$dispatchArticleData->saldo,
                 name:$dispatchArticleData->name,
                 subtotal_weight:$dispatchArticleData->subtotal_weight
           );
        })->toArray();
    }
}