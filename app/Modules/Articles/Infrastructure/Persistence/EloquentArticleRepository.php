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
        $eloquentArticle->refresh();

        $companyId = request()->get('company_id');

        $sucursales = request()->get('branches');

        $userId = request()->get('user_id');

        collect($sucursales)->each(function ($branchId) use ($companyId, $eloquentArticle, $userId) {
            EloquentVisibleArticle::create([
                'company_id' => $companyId,
                'branch_id' => $branchId,
                'article_id' => $eloquentArticle->id,
                'user_id' => $userId,
                'status' => true
            ]);
        });

        return $this->buildDomainSale($eloquentArticle, $article);
    }

    public function cretaArticleNotasDebito(ArticleNotasDebito $article): ?ArticleNotasDebito
    {

        $eloquentArticle = EloquentArticle::create([
            'description' => $article->getFiltNameEsp(),
            'user_id' => $article->getUserId(),
            'company_type_id' => $article->getCompanyId(),
            'status_Esp' => true,
            'category_id' => 1,
        ]);

        return new ArticleNotasDebito(
            id: $eloquentArticle->id,
            user_id: $article->getUserId(),
            company_id: $article->getCompanyId(),
            filt_NameEsp: $eloquentArticle->description,
            status_Esp: $article->getStatusEsp()

        );
    }

    public function findAllArticle(?string $description, ?int $branchId, ?int $brand_id, ?int $category_id, ?int $status)
    {
        $companyId = request()->get('company_id');
        $articles = EloquentArticle::where('company_type_id', $companyId)->where('status_Esp', false)
            ->when($description, function ($query, $name) use ($branchId) {
                return $query->where(function ($mainGroup) use ($name, $branchId) {
                    // Grupo 1: Búsqueda por Nombre/Código/Referencia (Validar con visibleArticles)
                    $mainGroup->where(function ($subQ) use ($name, $branchId) {
                        $subQ->where(function ($textQ) use ($name) {
                            $textQ->where('description', 'like', "%{$name}%")
                                ->orWhere('cod_fab', 'like', "%{$name}%")
                                ->orWhereHas('referenceCodes', function ($r) use ($name) {
                                    $r->where('ref_code', 'like', "%{$name}%");
                                });
                        });

                        if ($branchId) {
                            $subQ->whereHas('visibleArticles', function ($v) use ($branchId) {
                                $v->where('branch_id', $branchId);
                                $v->where('status', 1);
                            });
                        }
                    });
                });
            })
            ->when($branchId, function ($query, $branch) {
                return $query->whereHas('visibleArticles', function ($v) use ($branch) {
                    $v->where('branch_id', $branch);
                    $v->where('status', 1);
                });
            })
            // Filtro por marca
            ->when($brand_id, function ($query, $brand) {
                return $query->where('brand_id', $brand);
            })
            // Filtro por categoría
            ->when($category_id, function ($query, $category) {
                return $query->where('category_id', $category);
            })
            // Filtro por estado
            ->when($status !== null, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('description')
            ->paginate(10);

        // Transform the items in the paginator
        $articles->getCollection()->transform(function ($article) {
            return $this->mapToDomain($article);
        });

        return $articles;
    }



    public function findAllArticleNotesDebito(?string $description)
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
            ->when($description, function ($query, $description) {
                return $query->where(function ($q) use ($description) {
                    $q->where('description', 'like', "%{$description}%")
                        ->orWhere('cod_fab', 'like', "%{$description}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        $articles->getCollection()->transform(fn($article) => new ArticleNotasDebito(
            id: $article->id,
            user_id: $article->user_id,
            company_id: $article->company_type_id,
            filt_NameEsp: $article->description,
            status_Esp: $article->status_Esp
        ));
        return $articles;
    }

    public function findById(int $id): ?Article
    {
        $article = EloquentArticle::with(['measurementUnit', 'brand', 'category', 'currencyType', 'subCategory', 'company'])
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
            filt_NameEsp: $article->description,
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
            'description' => $article->getFiltNameEsp(),
            // 'status_Esp' => $article->getStatusEsp()
        ]);
        return new ArticleNotasDebito(
            id: $eloquentArticle->id,
            user_id: $eloquentArticle->user_id,
            company_id: $eloquentArticle->company_type_id,
            filt_NameEsp: $eloquentArticle->description,
            status_Esp: $eloquentArticle->statusEsp
        );
    }

    public function findAllArticlePriceConvertion(string $date, ?string $description, ?int $articleId, ?int $branchId, ?int $priceArticleId)
    {
        $companyId = request()->get('company_id');
        $exchangeRate = EloquentExchangeRate::select('parallel_rate')->where('date', $date)->first();
        $articles = EloquentArticle::where('company_type_id', $companyId)
            ->when($articleId, function ($query, $id) {
                // Obtener los IDs de accesorios compatibles desde DetailPcCompatible
                $accessoryIds = \App\Modules\DetailPcCompatible\Infrastructure\Models\EloquentDetailPcCompatible::where('article_major_id', $id)
                    ->pluck('article_accesory_id')
                    ->toArray();
                // Filtrar artículos cuyos IDs estén en la lista de accesorios
                return $query->whereIn('id', $accessoryIds);
            })
            ->when($priceArticleId, function ($query, $id) use ($branchId) {
                $query->where('id', $id);
                if ($branchId) {
                    $query->whereHas('visibleArticles', function ($v) use ($branchId) {
                        $v->where('branch_id', $branchId)
                            ->where('status', 1);
                    });
                }
            })
            ->when($description, function ($query, $name) use ($branchId) {
                return $query->where(function ($mainGroup) use ($name, $branchId) {
                    // Grupo 1: Búsqueda por Nombre/Código/Referencia (Validar con visibleArticles)
                    $mainGroup->where(function ($subQ) use ($name, $branchId) {
                        $subQ->where(function ($textQ) use ($name) {
                            $textQ->where('description', 'like', "%{$name}%")
                                ->orWhere('cod_fab', 'like', "%{$name}%")
                                ->orWhereHas('referenceCodes', function ($r) use ($name) {
                                    $r->where('ref_code', 'like', "%{$name}%");
                                });
                        });

                        if ($branchId) {
                            $subQ->whereHas('visibleArticles', function ($v) use ($branchId) {
                                $v->where('branch_id', $branchId);
                                $v->where('status', 1);
                            });
                        }
                    })
                        // Grupo 2: Búsqueda por Serie (Validar con entryItemSerials y visibleArticles)
                        ->orWhere(function ($subQ) use ($name, $branchId) {
                        if ($branchId) {
                            $subQ->whereHas('entryItemSerials', function ($s) use ($name, $branchId) {
                                $s->where('serial', $name)
                                    ->where('branch_id', $branchId);
                            });
                            // Validar también que sea visible en la sucursal
                            $subQ->whereHas('visibleArticles', function ($v) use ($branchId) {
                                $v->where('branch_id', $branchId)
                                    ->where('status', 1);
                            });
                        }
                    });
                });
            })
            // Si NO hay descripción, mantener el filtro original (solo lo que tiene stock/series en la sucursal)
            // PERO: si se proporciona articleId, no aplicar este filtro para permitir mostrar accesorios compatibles
            ->when(!$description && $branchId && !$articleId && !$priceArticleId, function ($query) use ($branchId) {
                return $query->whereHas('entryItemSerials', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10);
        // Transform the items in the paginator
        $articles->getCollection()->transform(function ($article) use ($exchangeRate) {
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
        });

        return $articles;
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

    public function requiredSerial(int $articleId): bool
    {
        $article = EloquentArticle::find($articleId);
        return $article->series_enabled;
    }

    public function updateStatus(int $articleId, int $status = 1): void
    {
        EloquentArticle::where('id', $articleId)->update(['status' => $status]);
    }

    public function findAllCombos(?string $name): array
    {
        $articles = EloquentArticle::where('url_supplier', true)
            ->when($name, function ($query, $name) {
                return $query->where(function ($q) use ($name) {
                    $q->where('description', 'like', "%{$name}%")
                        ->orWhere('cod_fab', 'like', "%{$name}%");
                });
            })
            ->get();

        return $articles->map(fn($article) => $this->mapToDomain($article))->all();
    }

    public function findArticlesByPlacaMadre(?string $description, int $branchId)
    {
        return EloquentArticle::query()
            //->where('url_supplier', true)
            ->where('category_id', 2)
            ->whereHas('visibleArticles', fn($query) =>
                $query->where('branch_id', $branchId)
                    ->where('status', 1))
            ->when($description, function ($query, $description) {
                return $query->where('description', 'like', "%{$description}%");
            })
            ->orderBy('id', 'asc')
            ->cursorPaginate(10);
    }


    private function mapToArray(Article $article): array
    {
        return [
            'cod_fab' => $article->getCodFab() ?? (string)$article->getId(),
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
            'brand_id' => $article->getBrand()?->getId(),
            'venta' => $article->getVenta(),
            'user_id' => $article->getUser()?->getId(),
            'category_id' => $article->getCategory()?->getId(),
            'sub_category_id' => $article->getSubCategory()?->getId(),
            'company_type_id' => $article->getCompany()?->getId(),
            'image_url' => $article->getImageURL(),
            'state_modify_article' => $article->getstateModifyArticle(),
            'filtNameEsp' => $article->getFiltNameEsp(),
            'statusEsp' => $article->getStatusEsp(),
            'url_supplier' => $article->getIsCombo(),
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
            statusEsp: $Eloquentarticle->statusEsp,
            url_supplier: $Eloquentarticle->url_supplier,
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
            url_supplier: $article->url_supplier,
        );
    }
}
