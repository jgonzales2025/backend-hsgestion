<?php
namespace App\Modules\Articles\Infrastructure\Persistence;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\VisibleArticles\Infrastructure\Models\EloquentVisibleArticle;
use Illuminate\Http\UploadedFile;
class EloquentArticleRepository implements ArticleRepositoryInterface
{

    public function save(Article $article): ?Article
    {
       

        $eloquentArticle = EloquentArticle::create([
            'cod_fab' => $article->getCodFab(),
            'description' => $article->getDescription(),
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
            'category_id' => $article->getCategory()->getId(),
            'sub_category_id' => $article->getSubCategory()->getId(),
            'company_type_id' => $article->getCompany()->getId(),
            'image_url' => $article->getImageURL(),
            'state_modify_article' =>$article->getstateModifyArticle()
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

        return new Article(
            id: $eloquentArticle->id,
            cod_fab: $eloquentArticle->cod_fab,
            description: $eloquentArticle->description,
            weight: (float) $eloquentArticle->weight,
            with_deduction: (bool) $eloquentArticle->with_deduction,
            series_enabled: (bool) $eloquentArticle->series_enabled,
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
            subCategory: $eloquentArticle->subCategory->toDomain($eloquentArticle->subCategory),
            company: $eloquentArticle->company->toDomain($eloquentArticle->company),
            image_url: $eloquentArticle->image_url,
            state_modify_article:$eloquentArticle->state_modify_article
        );
    }

    public function findAllArticle(?string $name, ?string $sku, ?string $serie): array
    {
        $payload = auth('api')->payload();
        $companyId = $payload->get('company_id');

        $articles = EloquentArticle::with([
            'measurementUnit',
            'brand',
            'category',
            'currencyType',
            'subCategory',
            'user',
            'company',
        ])->where('company_type_id', $companyId)
            ->when($name, function($query, $name){
                 return $query->where(function($q)use($name){
                       $q->where('description','like', "%{$name}")
                       ->orwhere('sku','like',"%{$name}")
                           ->orWhere('brand_id', function ($subQuery) use ($name) {
                    $subQuery->select('id')
                        ->from('brands')
                        ->where('name', 'like', "%{$name}%");
              });;
                 });
            })->orderByDesc('created_at')->get();


        return $articles->map(function ($article) {

            return new Article(
                id: $article->id,
                user: $article->user ? $article->user->toDomain($article->user) : null,
                cod_fab: $article->cod_fab,
                description: $article->description,
                weight: $article->weight,
                with_deduction: $article->with_deduction,
                series_enabled: $article->series_enabled,
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
                brand: $article->brand ? $article->brand->toDomain($article->brand) : null,
                category: $article->category ? $article->category->toDomain($article->category) : null,
                currencyType: $article->currencyType ? $article->currencyType->toDomain($article->currencyType) : null,
                measurementUnit: $article->measurementUnit ? $article->measurementUnit->toDomain($article->measurementUnit) : null,
                subCategory: $article->subCategory ? $article->subCategory->toDomain($article->subCategory) : null,
                precioIGv: $article->purchase_price + ($article->purchase_price * ($article->tariff_rate / 100)),
                venta: $article->venta,
                company: $article->company->toDomain($article->company),
                image_url: $article->image_url,
                state_modify_article:$article->state_modify_article

            );

        })->toArray();

    }


    public function findById(int $id): ?Article
    {

        $article = EloquentArticle::with(['measurementUnit', 'brand', 'category', 'currencyType', 'subCategory', 'company'])->find($id);

        if (!$article)
            return null;

        return new Article(
            id: $article->id,
            cod_fab: $article->cod_fab,
            user: $article->user->toDomain($article->user),
            description: $article->description,
            weight: $article->weight,
            with_deduction: $article->with_deduction,
            series_enabled: $article->series_enabled,
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
            subCategory: $article->subCategory->toDomain($article->subCategory) ?? null,
            company: $article->company->toDomain($article->company),
            image_url: $article->image_url,
             state_modify_article:$article->state_modify_article
        );
    }

    public function update(Article $article): void
    {
        $eloquentArticle = EloquentArticle::with(['measurementUnit', 'brand', 'category', 'currencyType', 'subCategory'])->find($article->getId());

        if (!$eloquentArticle) {
            throw new \Exception('Articulo no encontrado');
        }
        $eloquentArticle->update([
            'cod_fab' => $article->getCodFab(),
            'description' => $article->getDescription(),
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
            'category_id' => $article->getCategory()->getId(),
            'sub_category_id' => $article->getSubCategory()->getId(),
            'image_url' => $article->getImageURL(),
             'state_modify_article' =>$article->getstateModifyArticle()
            
        ]);
    }
}
