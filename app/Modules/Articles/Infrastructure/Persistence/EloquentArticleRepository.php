<?php
namespace App\Modules\Articles\Infrastructure\Persistence;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use Illuminate\Support\Facades\Log;

class EloquentArticleRepository implements ArticleRepositoryInterface{
   
     public function save(Article $article):Article{
            $eloquentArticle = EloquentArticle::create([
            'cod_fab'                    => $article->getCodFab(),
            'description'                => $article->getDescription(),
            'short_description'          => $article->getShortDescription(),
            'weight'                     => $article->getWeight(),
            'with_deduction'             => $article->getWithDeduction(),
            'series_enabled'             => $article->getSeriesEnabled(),
            'measurement_unit_id'        => $article->getMeasurementUnitId(),
            'brand_id'                   => $article->getBrandId(),
            'category_id'                => $article->getCategoryId(),
            'location'                   => $article->getLocation(),
            'warranty'                   => $article->getWarranty(),
            'tariff_rate'                => $article->getTariffRate(),
            'igv_applicable'             => $article->getIgvApplicable(),
            'plastic_bag_applicable'     => $article->getPlasticBagApplicable(),
            'min_stock'                  => $article->getMinStock(),
            'currency_type_id'           => $article->getCurrencyTypeId(),
            'cost_to_price_percent'      => $article->getCostToPricePercent(),
            'purchase_price'             => $article->getPurchasePrice(),
            'public_price'               => $article->getPublicPrice(),
            'distributor_price'          => $article->getDistributorPrice(),
            'authorized_price'           => $article->getAuthorizedPrice(),
            'public_price_percent'       => $article->getPublicPricePercent(),
            'distributor_price_percent'  => $article->getDistributorPricePercent(),
            'authorized_price_percent'   => $article->getAuthorizedPricePercent(),
            'status'                     => $article->getStatus() ,
            'user_id'                    => $article->getUserId(),
            ]);
            return new Article(
            id: $eloquentArticle->id,
            cod_fab: $eloquentArticle->cod_fab,
            description: $eloquentArticle->description,
            short_description: $eloquentArticle->short_description,
            weight: $eloquentArticle->weight,
            with_deduction: $eloquentArticle->with_deduction,
            series_enabled: $eloquentArticle->series_enabled,
            measurement_unit_id: $eloquentArticle->measurement_unit_id,
            brand_id: $eloquentArticle->brand_id,
            category_id: $eloquentArticle->category_id,
            location: $eloquentArticle->location,
            warranty: $eloquentArticle->warranty,
            tariff_rate: $eloquentArticle->tariff_rate,
            igv_applicable: $eloquentArticle->igv_applicable,
            plastic_bag_applicable: $eloquentArticle->plastic_bag_applicable,
            min_stock: $eloquentArticle->min_stock,
            currency_type_id: $eloquentArticle->currency_type_id,
            cost_to_price_percent: $eloquentArticle->cost_to_price_percent,
            purchase_price: $eloquentArticle->purchase_price,
            public_price: $eloquentArticle->public_price,
            distributor_price: $eloquentArticle->distributor_price,
            authorized_price: $eloquentArticle->authorized_price,
            public_price_percent: $eloquentArticle->public_price_percent,
            distributor_price_percent: $eloquentArticle->distributor_price_percent,
            authorized_price_percent: $eloquentArticle->authorized_price_percent,
            status: $eloquentArticle->status,
            user_id: $eloquentArticle->user_id
            );
     }
     public function findAllArticle(): array {
            $articles = EloquentArticle::all();
            if ($articles->isEmpty()) {
            return [];
        }
        return $articles->map(fn($article) => new Article(
            id: $article->id,
            cod_fab: $article->cod_fab,
            description: $article->description,
            short_description: $article->short_description,
            weight: $article->weight,
            with_deduction: $article->with_deduction,
            series_enabled: $article->series_enabled,
            measurement_unit_id: $article->measurement_unit_id,
            brand_id: $article->brand_id,
            category_id: $article->category_id,
            location: $article->location,
            warranty: $article->warranty,
            tariff_rate: $article->tariff_rate,
            igv_applicable: $article->igv_applicable,
            plastic_bag_applicable: $article->plastic_bag_applicable,
            min_stock: $article->min_stock,
            currency_type_id: $article->currency_type_id,
            cost_to_price_percent: $article->cost_to_price_percent,
            purchase_price: $article->purchase_price,
            public_price: $article->public_price,
            distributor_price: $article->distributor_price,
            authorized_price: $article->authorized_price,
            public_price_percent: $article->public_price_percent,
            distributor_price_percent: $article->distributor_price_percent,
            authorized_price_percent: $article->authorized_price_percent,
            status: $article->status,
            user_id: $article->user_id
        ))->toArray();
     }
public function findById(int $id): ?Article
{
    $article = EloquentArticle::with(['measurementUnit', 'brand'])->find($id);

    if (!$article) return null;

    return new Article(
        id: $article->id,
        cod_fab: $article->cod_fab,
        description: $article->description,
        short_description: $article->short_description,
        weight: $article->weight,
        with_deduction:$article->with_deduction,
        series_enabled: $article->series_enabled,
        measurement_unit_id: $article->measurement_unit_id,
        brand_id: $article->brand_id,
        category_id: $article->category_id,
        location: $article->location,
        warranty: $article->warranty,
        tariff_rate: $article->tariff_rate,
        igv_applicable: $article->igv_applicable,
        plastic_bag_applicable: $article->plastic_bag_applicable,
        min_stock: $article->min_stock,
        currency_type_id: $article->currency_type_id,
        cost_to_price_percent: $article->cost_to_price_percent,
        purchase_price: $article->purchase_price,
        public_price: $article->public_price,
        distributor_price: $article->distributor_price,
        authorized_price: $article->authorized_price,
        public_price_percent: $article->public_price_percent,
        distributor_price_percent: $article->distributor_price_percent,
        authorized_price_percent: $article->authorized_price_percent,
        status: $article->status,
        user_id: $article->user_id,
        brand: $article->brand ? $article->brand->toArray() : null, // 👈 aquí
    );
}

      public function update(Article $article): void{
            $eloquentArticle = EloquentArticle::find($article->getId());
               Log::info('Articulo encontrado', ['articles' => $eloquentArticle]); 
      
           
            if (!$eloquentArticle) {
                throw new \Exception('Articulo no encontrado');
            }
            $eloquentArticle->update([
                    'cod_fab' => $article->getCodFab(),
            'description' => $article->getDescription(),
            'short_description' => $article->getShortDescription(),
            'weight' => $article->getWeight(),
            'with_deduction' => $article->getWithDeduction(),
            'series_enabled' => $article->getSeriesEnabled(),
            'measurement_unit_id' => $article->getMeasurementUnitId(),
            'brand_id' => $article->getBrandId(),
            'category_id' => $article->getCategoryId(),
            'location' => $article->getLocation(),
            'warranty' => $article->getWarranty(),
            'tariff_rate' => $article->getTariffRate(),
            'igv_applicable' => $article->getIgvApplicable(),
            'plastic_bag_applicable' => $article->getPlasticBagApplicable(),
            'min_stock' => $article->getMinStock(),
            'currency_type_id' => $article->getCurrencyTypeId(),
            'cost_to_price_percent' => $article->getCostToPricePercent(),
            'purchase_price' => $article->getPurchasePrice(),
            'public_price' => $article->getPublicPrice(),
            'distributor_price' => $article->getDistributorPrice(),
            'authorized_price' => $article->getAuthorizedPrice(),
            'public_price_percent' => $article->getPublicPricePercent(),
            'distributor_price_percent' => $article->getDistributorPricePercent(),
            'authorized_price_percent' => $article->getAuthorizedPricePercent(),
            'status' => $article->getStatus(),
            'user_id' => $article->getUserId(),
            ]);

           
     }
}