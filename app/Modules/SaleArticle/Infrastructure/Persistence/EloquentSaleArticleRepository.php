<?php

namespace App\Modules\SaleArticle\Infrastructure\Persistence;

use App\Modules\Sale\Infrastructure\Models\EloquentSale;
use App\Modules\SaleArticle\Domain\Entities\SaleArticle;
use App\Modules\SaleArticle\Domain\Interfaces\SaleArticleRepositoryInterface;
use App\Modules\SaleArticle\Infrastructure\Models\EloquentSaleArticle;

class EloquentSaleArticleRepository implements SaleArticleRepositoryInterface
{

    public function save(SaleArticle $saleArticle, float $subtotal_costo_neto): ?SaleArticle
    {
        $eloquentSaleArticle = EloquentSaleArticle::create([
            'sale_id' => $saleArticle->getSaleId(),
            'article_id' => $saleArticle->getArticle()->getId(),
            'description' => $saleArticle->getDescription(),
            'quantity' => $saleArticle->getQuantity(),
            'unit_price' => $saleArticle->getUnitPrice(),
            'public_price' => $saleArticle->getPublicPrice(),
            'subtotal' => $saleArticle->getSubtotal(),
            'purchase_price' => $saleArticle->getPurchasePrice(),
            'costo_neto' => $saleArticle->getCostoNeto()
        ]);

        EloquentSale::where('id', $saleArticle->getSaleId())->update([
            'total_costo_neto' => $subtotal_costo_neto
        ]);

        return new SaleArticle(
            id: $eloquentSaleArticle->id,
            sale_id: $eloquentSaleArticle->sale_id,
            sku: $eloquentSaleArticle->article->cod_fab,
            article: $saleArticle->getArticle(),
            description: $eloquentSaleArticle->description,
            quantity: $eloquentSaleArticle->quantity,
            unit_price: $eloquentSaleArticle->unit_price,
            public_price: $eloquentSaleArticle->public_price,
            subtotal: $eloquentSaleArticle->subtotal,
            purchase_price: $eloquentSaleArticle->purchase_price,
            costo_neto: $eloquentSaleArticle->costo_neto
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
                article: $eloquentSaleArticle->article->toDomain($eloquentSaleArticle->article),
                description: $eloquentSaleArticle->description,
                quantity: $eloquentSaleArticle->quantity,
                unit_price: $eloquentSaleArticle->unit_price,
                public_price: $eloquentSaleArticle->public_price,
                subtotal: $eloquentSaleArticle->subtotal,
                state_modify_article: $eloquentSaleArticle->article->state_modify_article,
                series_enabled: $eloquentSaleArticle->article->series_enabled,
                purchase_price: $eloquentSaleArticle->purchase_price,
                costo_neto: $eloquentSaleArticle->costo_neto
            );
        })->toArray();
    }

    public function deleteBySaleId(int $id): void
    {
        EloquentSaleArticle::where('sale_id', $id)->delete();
    }
}
