<?php

namespace App\Modules\SaleArticle\Infrastructure\Persistence;

use App\Modules\SaleArticle\Domain\Entities\SaleArticle;
use App\Modules\SaleArticle\Domain\Interfaces\SaleArticleRepositoryInterface;
use App\Modules\SaleArticle\Infrastructure\Models\EloquentSaleArticle;

class EloquentSaleArticleRepository implements SaleArticleRepositoryInterface
{

    public function save(SaleArticle $saleArticle): ?SaleArticle
    {
        $eloquentSaleArticle = EloquentSaleArticle::create([
            'sale_id' => $saleArticle->getSaleId(),
            'article_id' => $saleArticle->getArticleId(),
            'description' => $saleArticle->getDescription(),
            'quantity' => $saleArticle->getQuantity(),
            'unit_price' => $saleArticle->getUnitPrice(),
            'public_price' => $saleArticle->getPublicPrice(),
            'subtotal' => $saleArticle->getSubtotal(),
        ]);

        return new SaleArticle(
            id: $eloquentSaleArticle->id,
            sale_id: $eloquentSaleArticle->sale_id,
            sku: $eloquentSaleArticle->article->cod_fab,
            article_id: $eloquentSaleArticle->article_id,
            description: $eloquentSaleArticle->description,
            quantity: $eloquentSaleArticle->quantity,
            unit_price: $eloquentSaleArticle->unit_price,
            public_price: $eloquentSaleArticle->public_price,
            subtotal: $eloquentSaleArticle->subtotal,
        );
    }

    public function findBySaleId(int $sale_id): array
    {
        $eloquentSaleArticles = EloquentSaleArticle::where('sale_id', $sale_id)->get();

        return $eloquentSaleArticles->map(function ($eloquentSaleArticle) {
            return new SaleArticle(
                id: $eloquentSaleArticle->id,
                sale_id: $eloquentSaleArticle->sale_id,
                sku: $eloquentSaleArticle->article->cod_fab,
                article_id: $eloquentSaleArticle->article_id,
                description: $eloquentSaleArticle->description,
                quantity: $eloquentSaleArticle->quantity,
                unit_price: $eloquentSaleArticle->unit_price,
                public_price: $eloquentSaleArticle->public_price,
                subtotal: $eloquentSaleArticle->subtotal,
            );
        })->toArray();
    }

    public function deleteBySaleId(int $id): void
    {
        EloquentSaleArticle::where('sale_id', $id)->delete();
    }
}
