<?php
namespace App\Modules\Articles\Infrastructure\Persistence;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Articles\Domain\Entities\ArticleForSale;
use App\Modules\Articles\Domain\Entities\ArticleNotasDebito;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\Branch\Infrastructure\Models\EloquentBranch; 
use App\Modules\ExchangeRate\Infrastructure\Models\EloquentExchangeRate;
use App\Modules\VisibleArticles\Infrastructure\Models\EloquentVisibleArticle;
use Illuminate\Support\Collection; 

class EloquentArticleRepository implements ArticleRepositoryInterface
{
    public function save(Article $article): ?Article
    {

        $eloquentArticle = EloquentArticle::create($this->mapToArray($article));

        $companyId = request()->get('company_id');

        $sucursales = EloquentBranch::where('cia_id', $companyId)->get();

        $sucursales->map(function ($sucursal) use ($companyId, $eloquentArticle) {
            EloquentVisibleArticle::create([
                'company_id' => $companyId,
                'branch_id' => $sucursal->id,
                'article_id' => $eloquentArticle->id,
                'user_id' => 1,
                'status' => true
            ]);
        });

        return $this->buildDomainSale($eloquentArticle, $article);
    }

    public function cretaArticleNotasDebito(ArticleNotasDebito $article): ?ArticleNotasDebito
    {

        $eloquentArticle = EloquentArticle::create([
            'filt_NameEsp' => $article->getFiltNameEsp(),
            'user_id' => $article->getUserId(),
            'company_type_id' => $article->getCompanyId(),
            'status_Esp' => true,
            'category_id' => 1,
        ]);

        return new ArticleNotasDebito(
            id: $eloquentArticle->id,
            user_id: $article->getUserId(),
            company_id: $article->getCompanyId(),
            filt_NameEsp: $article->getFiltNameEsp(),
            status_Esp: $article->getStatusEsp()

        );
    }

    public function findAllArticle(?string $description): array
    {
        $companyId = request()->get('company_id');


        $articles = EloquentArticle::with([
            'measurementUnit',
            'brand',
            'category',
            'currencyType',
            'subCategory',
            'user',
            'company',
        ])
            ->where('company_type_id', $companyId)
            ->where('status_Esp', false)
            ->when($description, function ($query, $name) {
                return $query->where(function ($q) use ($name) {
                    $q->where('description', 'like', "%{$name}%")
                        ->orWhere('cod_fab', 'like', "%{$name}%")
                        
                        ;
                });
            })
            ->orderByDesc('created_at')
            ->get();

        return $articles->map(fn($article) => $this->mapToDomain($article))->toArray();
    }


    public function findAllArticleNotasDebito(?string $description): array
    {
        $companyId = request()->get('company_id');

        $articles = EloquentArticle::with([
            'measurementUnit',
            'brand',
            'category',
            'currencyType',
            'subCategory',
            'user',
            'company',
        ])
            ->where('company_type_id', $companyId)
            ->where('status_Esp', true)
            ->when($description, function ($query, $name) {
                return $query->where(function ($q) use ($name) {
                    $q->where('description', 'like', "%{$name}%")
                        ->orWhere('cod_fab', 'like', "%{$name}%");
                });
            })
            ->orderByDesc('created_at')
            ->get();

        return $articles->map(fn($article) => $this->mapToDomain($article))->toArray();
    }
    public function findAllArticleNotesDebito(?string $description): array
    {
        $companyId = request()->get('company_id');

        $articles = EloquentArticle::with([
            'measurementUnit',
            'brand',
            'category',
            'currencyType',
            'subCategory',
            'user',
            'company',
        ])
            ->where('company_type_id', $companyId)
            ->where('status_Esp', true)
            ->when($description, function ($query, $name) {
                return $query->where(function ($q) use ($name) {
                    $q->where('description', 'like', "%{$name}%")
                        ->orWhere('cod_fab', 'like', "%{$name}%");
                });
            })
            ->orderByDesc('created_at')
            ->get();

        return $articles->map(function ($article) {
            return new ArticleNotasDebito(
                id: $article->id,
                user_id: $article->user_id,
                company_id: $article->company_type_id,
                filt_NameEsp: $article->filt_NameEsp,
                status_Esp: $article->statusEsp

            );

        })->toArray();
    }

    public function findById(int $id): ?Article
    {

        $article = EloquentArticle::with(['measurementUnit', 'brand', 'category', 'currencyType', 'subCategory', 'company'])
            ->where('status_Esp', false)
            ->find($id);

        if (!$article)
            return null;

        return $this->mapToDomain($article);
    }
    public function FindByIdNotesDebito(int $id): ?ArticleNotasDebito
    {

        $article = EloquentArticle::find($id);

        if (!$article)
            return null;

        return new ArticleNotasDebito(
            id: $article->id,
            user_id: $article->user_id,
            company_id: $article->company_type_id,
            filt_NameEsp: $article->filt_NameEsp,
            status_Esp: $article->statusEsp

        );
    }
    public function update(Article $article): ?Article
    {
        $eloquentArticle = EloquentArticle::with(['measurementUnit', 'brand', 'category', 'currencyType', 'subCategory'])
            ->where('status_Esp', false)
            ->find($article->getId());

        if (!$eloquentArticle) {
            throw new \Exception('Articulo no encontrado');
        }
        $eloquentArticle->update($this->mapToArray($article));
        return $this->buildDomainSale($eloquentArticle, $article);
    }
    public function updateNotesDebito(ArticleNotasDebito $article): ?ArticleNotasDebito
    {
        $eloquentArticle = EloquentArticle::find($article->getId());

        if (!$eloquentArticle) {
            throw new \Exception('Articulo no encontrado');
        }
        $eloquentArticle->update([
            'filt_NameEsp' => $article->getFiltNameEsp(),
            // 'status_Esp' => $article->getStatusEsp()
        ]);
        return new ArticleNotasDebito(
            id: $eloquentArticle->id,
            user_id: $eloquentArticle->user_id,
            company_id: $eloquentArticle->company_type_id,
            filt_NameEsp: $eloquentArticle->filt_NameEsp,
            status_Esp: $eloquentArticle->statusEsp
        );
    }

    public function findAllArticlePriceConvertion(string $date, ?string $description): array
    {
        $companyId = request()->get('company_id');
        $exchangeRate = EloquentExchangeRate::select('parallel_rate')->where('date', $date)->first();

        $articles = EloquentArticle::where('company_type_id', $companyId)
            ->when($description, function ($query, $name) {
                return $query->where(function ($q) use ($name) {
                    $q->where('description', 'like', "%{$name}%")
                        ->orWhere('cod_fab', 'like', "%{$name}%");
                });
            })
            ->orderByDesc('created_at')
            ->get();

        return $articles->map(function ($article) use ($exchangeRate) {
            // Función para convertir precios
            $convertToUsd = function ($price) use ($exchangeRate) {
                if (!$exchangeRate || $exchangeRate->parallel_rate == 0)
                    return $price;
                return round($price / $exchangeRate->parallel_rate, 2);
            };

            $convertToPen = function ($price) use ($exchangeRate) {
                if (!$exchangeRate)
                    return $price;
                return round($price * $exchangeRate->parallel_rate, 2);
            };

            // Si el artículo está en Soles (currency_type_id = 1)
            if ($article->currency_type_id == 1) {
                $purchasePricePen = $article->purchase_price;
                $publicPricePen = $article->public_price;
                $distributorPricePen = $article->distributor_price;
                $authorizedPricePen = $article->authorized_price;

                $purchasePriceUsd = $convertToUsd($article->purchase_price);
                $publicPriceUsd = $convertToUsd($article->public_price);
                $distributorPriceUsd = $convertToUsd($article->distributor_price);
                $authorizedPriceUsd = $convertToUsd($article->authorized_price);
            }
            // Si el artículo está en Dólares (currency_type_id = 2)
            else {
                $purchasePriceUsd = $article->purchase_price;
                $publicPriceUsd = $article->public_price;
                $distributorPriceUsd = $article->distributor_price;
                $authorizedPriceUsd = $article->authorized_price;

                $purchasePricePen = $convertToPen($article->purchase_price);
                $publicPricePen = $convertToPen($article->public_price);
                $distributorPricePen = $convertToPen($article->distributor_price);
                $authorizedPricePen = $convertToPen($article->authorized_price);
            }

            return new ArticleForSale(
                id: $article->id,
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
                purchase_price_pen: $purchasePricePen,
                public_price_pen: $publicPricePen,
                distributor_price_pen: $distributorPricePen,
                authorized_price_pen: $authorizedPricePen,
                purchase_price_usd: $purchasePriceUsd,
                public_price_usd: $publicPriceUsd,
                distributor_price_usd: $distributorPriceUsd,
                authorized_price_usd: $authorizedPriceUsd,
                public_price_percent: $article->public_price_percent,
                distributor_price_percent: $article->distributor_price_percent,
                authorized_price_percent: $article->authorized_price_percent,
                status: $article->status,
                brand: $article->brand?->toDomain($article->brand),
                category: $article->category?->toDomain($article->category),
                currencyType: $article->currencyType?->toDomain($article->currencyType),
                measurementUnit: $article->measurementUnit?->toDomain($article->measurementUnit),
                user: $article->user?->toDomain($article->user),
                venta: $article->venta,
                subCategory: $article->subCategory?->toDomain($article->subCategory),
                company: $article->company?->toDomain($article->company),
                image_url: $article->image_url,
                state_modify_article: $article->state_modify_article,

            );
        })->toArray();

    }

    public function findAllExcel(?string $description): Collection
    {
        $companyId = request()->get('company_id');

        $articles = EloquentArticle::with([
            'measurementUnit',
            'brand',
            'category',
            'currencyType',
            'subCategory',
            'user',
            'company',
        ])
            ->where('company_type_id', $companyId)
            ->where('status_Esp', false)
            ->when($description, function ($query, $name) {
                return $query->where(function ($q) use ($name) {
                    $q->where('description', 'like', "%{$name}%")
                        ->orWhere('cod_fab', 'like', "%{$name}%");
                });
            })
            ->orderByDesc('created_at')
            ->get();

        return $articles->map(fn($article) => $this->mapToDomain($article));
    }
    private function mapToArray(Article $article): array
    {
        return [
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
            'currency_type_id' => $article->getCurrencyType()?->getId(),
            'purchase_price' => $article->getPurchasePrice(),
            'public_price' => $article->getPublicPrice(),
            'distributor_price' => $article->getDistributorPrice(),
            'authorized_price' => $article->getAuthorizedPrice(),
            'measurement_unit_id' => $article->getMeasurementUnit()?->getId(),
            'public_price_percent' => $article->getPublicPricePercent(),
            'distributor_price_percent' => $article->getDistributorPricePercent(),
            'authorized_price_percent' => $article->getAuthorizedPricePercent(),
            'status' => $article->getStatus(),
            'brand_id' => $article->getBrand()?->getId(),
            'venta' => $article->getVenta(),
            'user_id' => $article->getUser()?->getId(),
            'category_id' => $article->getCategory()?->getId(),
            'sub_category_id' => $article->getSubCategory()?->getId(),
            'company_type_id' => $article->getCompany()?->getId(),
            'image_url' => $article->getImageURL(),
            'state_modify_article' => $article->getstateModifyArticle(),
            'filtNameEsp' => $article->getFiltNameEsp(),
            'statusEsp' => $article->getStatusEsp()
        ];
    }
    private function buildDomainSale(EloquentArticle $Eloquentarticle, Article $article): Article
    {
        return new Article(
            id: $Eloquentarticle->id,
            cod_fab: $Eloquentarticle->cod_fab,
            description: $Eloquentarticle->description,
            weight: $Eloquentarticle->weight,
            with_deduction: $Eloquentarticle->with_deduction,
            series_enabled: $Eloquentarticle->series_enabled,
            location: $Eloquentarticle->location,
            warranty: $Eloquentarticle->warranty,
            tariff_rate: $Eloquentarticle->tariff_rate,
            igv_applicable: $Eloquentarticle->igv_applicable,
            plastic_bag_applicable: $Eloquentarticle->plastic_bag_applicable,
            min_stock: $Eloquentarticle->min_stock,
            purchase_price: $article->getPurchasePrice(),
            public_price: $article->getPublicPrice(),
            distributor_price: $article->getDistributorPrice(),
            authorized_price: $article->getAuthorizedPrice(),
            public_price_percent: $Eloquentarticle->public_price_percent,
            distributor_price_percent: $Eloquentarticle->distributor_price_percent,
            authorized_price_percent: $Eloquentarticle->authorized_price_percent,
            status: $Eloquentarticle->status,
            brand: $article->getBrand(),
            category: $article->getCategory(),
            currencyType: $article->getCurrencyType(),
            measurementUnit: $article->getMeasurementUnit(),
            subCategory: $article->getSubCategory(),
            user: $article->getUser(),
            venta: $Eloquentarticle->venta,
            company: $article->getCompany(),
            image_url: $Eloquentarticle->image_url,
            state_modify_article: $Eloquentarticle->state_modify_article,
            filtNameEsp: $Eloquentarticle->filtNameEsp,
            statusEsp: $Eloquentarticle->statusEsp
        );
    }
    private function mapToDomain(EloquentArticle $article): Article
    {
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
            venta: $article->venta,
            company: $article->company ? $article->company->toDomain($article->company) : null,
            image_url: $article->image_url,
            state_modify_article: $article->state_modify_article,
            filtNameEsp: $article->filtNameEsp,
            statusEsp: $article->statusEsp,
        );
    }
}
