<?php
namespace App\Modules\Articles\Infrastructure\Persistence;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\VisibleArticles\Infrastructure\Models\EloquentVisibleArticle;
use Illuminate\Support\Facades\Log;

class EloquentArticleRepository implements ArticleRepositoryInterface
{

    public function save(Article $article): ?Article
    {


        $eloquentArticle = EloquentArticle::create([
            'cod_fab' => $article->getCodFab(),
            'description' => $article->getDescription(),
            'short_description' => $article->getShortDescription(),
            'weight' => $article->getWeight(),
            'with_deduction' => $article->getWithDeduction(),
            'series_enabled' => $article->getSeriesEnabled(),
            'location' => $article->getLocation(),
            'warranty' => $article->getWarranty(),
            'tariff_rate' => $article->getTariffRate(),
            'igv_applicable' => $article->getIgvApplicable(),
            'plastic_bag_applicable' => $article->getPlasticBagApplicable(),
            'min_stock' => $article->getMinStock(),
            'currency_type_id' => $article->getCurrencyType()->getId(),
            'purchase_price' => $article->getPurchasePrice(),
            'public_price' => $article->getPublicPrice(),
            'distributor_price' => $article->getDistributorPrice(),
            'authorized_price' => $article->getAuthorizedPrice(),
            'measurement_unit_id' => $article->getMeasurementUnit()->getId(),
            'public_price_percent' => $article->getPublicPricePercent(),
            'distributor_price_percent' => $article->getDistributorPricePercent(),
            'authorized_price_percent' => $article->getAuthorizedPricePercent(),
            'status' => $article->getStatus(),
            'brand_id' => $article->getBrand()->getId(),
            'venta' => $article->getVenta(),
            'user_id' => $article->getUser()->getId(),
            // 'category_id' =>$article->getSubCategory(),
            'category_id' => $article->getCategory()->getId()
        ]);

        $payload = auth('api')->payload();
        $companyId = $payload->get('company_id');

        $sucursales = EloquentBranch::where('cia_id', $companyId)->get();

        $sucursales->map(function ($sucursal) use ($companyId, $eloquentArticle) {
            EloquentVisibleArticle::create([
                'company_id' => $companyId,
                'branch_id' => $sucursal->id,
                'article_id' => $eloquentArticle->id,
                'user_id' => $eloquentArticle->user_id,
                'status' => 1
            ]);
        });
        // Log::info('eloquentArticle',$eloquentArticle);
        return new Article(
            id: $eloquentArticle->id,
            cod_fab: $eloquentArticle->cod_fab,
            description: $eloquentArticle->description,
            short_description: $eloquentArticle->short_description,
            weight: (float) $eloquentArticle->weight,
            with_deduction: (bool) $eloquentArticle->with_deduction,
            series_enabled: (bool) $eloquentArticle->series_enabled,
            // measurement_unit_id: $eloquentArticle->measurement_unit_id,
            // brand_id: $eloquentArticle->brand_id,
            //category_id: $eloquentArticle->category_id,
            location: $eloquentArticle->location,
            warranty: $eloquentArticle->warranty,
            tariff_rate: (float) $eloquentArticle->tariff_rate,
            igv_applicable: (bool) $eloquentArticle->igv_applicable,
            plastic_bag_applicable: (bool) $eloquentArticle->plastic_bag_applicable,
            min_stock: $eloquentArticle->min_stock,
            purchase_price: (float) $eloquentArticle->purchase_price,
            public_price: (float) $eloquentArticle->public_price,
            distributor_price: (float) $eloquentArticle->distributor_price,
            authorized_price: (float) $eloquentArticle->authorized_price,
            public_price_percent: (float) $eloquentArticle->public_price_percent,
            distributor_price_percent: (float) $eloquentArticle->distributor_price_percent,
            authorized_price_percent: isset($eloquentArticle->authorized_price_percent) ? (float) $eloquentArticle->authorized_price_percent : 0,
            status: $eloquentArticle->status,
            brand: $eloquentArticle->brand->toDomain($eloquentArticle->brand),
            category: $eloquentArticle->category->toDomain($eloquentArticle->category),
            currencyType: $eloquentArticle->currencyType->toDomain($eloquentArticle->currencyType),
            measurementUnit: $eloquentArticle->measurementUnit->toDomain($eloquentArticle->measurementUnit),
            user: $eloquentArticle->user->toDomain($eloquentArticle->user),
            precioIGv: isset($eloquentArticle->purchase_price, $eloquentArticle->tariff_rate)
            ? (float) ($eloquentArticle->purchase_price + ($eloquentArticle->purchase_price * $eloquentArticle->tariff_rate / 100))
            : 0,
            venta: (bool) $eloquentArticle->venta,
        );
    }

    public function findAllArticle(): array
    {
        $payload = auth('api')->payload();
        $companyId = $payload->get('company_id');

        $Eloquentvisiblearticles = EloquentVisibleArticle::where('company_id', $companyId)
            ->get();

        return $Eloquentvisiblearticles->map(function ($article) {
            $articleType = EloquentArticle::with(
                'measurementUnit'
                ,
                'brand',
                'category',
                'currencyType',
                'user'
            )->find($article->article_id);


            return new Article(
                id: $articleType->id,
                user: $articleType->user->toDomain($articleType->user),
                cod_fab: $articleType->cod_fab,
                description: $articleType->description,
                short_description: $articleType->short_description,
                weight: $articleType->weight,
                with_deduction: $articleType->with_deduction,
                series_enabled: $articleType->series_enabled,
                // user:$article->user,
                // measurement_unit_id: $article->measurement_unit_id,
                // brand_id: $article->brand_id,
                // category_id: $article->category_id,
                location: $articleType->location,
                warranty: $articleType->warranty,
                tariff_rate: $articleType->tariff_rate,
                igv_applicable: $articleType->igv_applicable,
                plastic_bag_applicable: $articleType->plastic_bag_applicable,
                min_stock: $articleType->min_stock,
                purchase_price: $articleType->purchase_price,
                public_price: $articleType->public_price,
                distributor_price: $articleType->distributor_price,
                authorized_price: $articleType->authorized_price,
                public_price_percent: $articleType->public_price_percent,
                distributor_price_percent: $articleType->distributor_price_percent,
                authorized_price_percent: $articleType->authorized_price_percent,
                status: $articleType->status,

                brand: $articleType->brand->toDomain($articleType->brand) ?? null,
                category: $articleType->category->toDomain($articleType->category) ?? null,
                currencyType: $articleType->currencyType->toDomain($articleType->currencyType) ?? null,
                precioIGv: $articleType->purchase_price + ($articleType->purchase_price * ($articleType->tariff_rate / 100)),
                measurementUnit: $articleType->measurementUnit->toDomain($articleType->measurementUnit) ?? null,
                venta: $articleType->venta,


            );
        })->toArray();
    }

    public function findById(int $id): ?Article
    {
        $article = EloquentArticle::with(['measurementUnit', 'brand', 'category', 'currencyType'])->find($id);

        // $precioIGv = $article->purchase_price + ($article->purchase_price * ($article->tariff_rate / 100));

        if (!$article)
            return null;

        return new Article(
            id: $article->id,
            cod_fab: $article->cod_fab,
            user: $article->user->toDomain($article->user),
            description: $article->description,
            short_description: $article->short_description,
            weight: $article->weight,
            with_deduction: $article->with_deduction,
            series_enabled: $article->series_enabled,
            // measurement_unit_id: $article->measurement_unit_id,
            // brand_id: $article->brand_id,
            // category_id: $article->category_id,
            location: $article->location,
            warranty: $article->warranty,
            tariff_rate: $article->tariff_rate,
            igv_applicable: $article->igv_applicable,
            plastic_bag_applicable: $article->plastic_bag_applicable,
            min_stock: $article->min_stock,
            purchase_price: $article->purchase_price,
            public_price: $article->public_price,
            distributor_price: $article->distributor_price,
            authorized_price: $article->authorized_price,
            public_price_percent: $article->public_price_percent,
            distributor_price_percent: $article->distributor_price_percent,
            authorized_price_percent: $article->authorized_price_percent,
            status: $article->status,

            brand: $article->brand->toDomain($article->brand) ?? null,
            category: $article->category->toDomain($article->category) ?? null,
            currencyType: $article->currencyType->toDomain($article->currencyType) ?? null,
            precioIGv: $article->purchase_price + ($article->purchase_price * ($article->tariff_rate / 100)),
            measurementUnit: $article->measurementUnit->toDomain($article->measurementUnit) ?? null,
            venta: $article->venta,


        );
    }

    public function update(Article $article): void
    {
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
            'purchase_price' => $article->getPurchasePrice(),
            'public_price' => $article->getPublicPrice(),
            'distributor_price' => $article->getDistributorPrice(),
            'authorized_price' => $article->getAuthorizedPrice(),
            'public_price_percent' => $article->getPublicPricePercent(),
            'distributor_price_percent' => $article->getDistributorPricePercent(),
            'authorized_price_percent' => $article->getAuthorizedPricePercent(),
            'status' => $article->getStatus(),
            'user_id' => $article->getUserId(),
            'brand' => $article->getBrand(),
            'category' => $article->getCategory(),
            'currencyType' => $article->getCurrencyType(),
            'venta' => $article->getVenta(),
            'subcategory_id' => $article->getSubcategoriaId()
        ]);


    }
}

//  $table->foreign('measurement_unit_id')->references('id')->on('measurement_units');
//     $table->foreign('brand_id')->references('id')->on('brands');
//     $table->foreign('category_id')->references('id')->on('categories');
//     $table->foreign('currency_type_id')->references('id')->on('currency_types');
//     $table->foreign('user_id')->references('id')->on('users');
//     $table->timestamp('date_at')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
