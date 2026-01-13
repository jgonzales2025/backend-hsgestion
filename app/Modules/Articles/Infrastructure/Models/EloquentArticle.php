<?php

namespace App\Modules\Articles\Infrastructure\Models;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Brand\Infrastructure\Models\EloquentBrand;
use App\Modules\Category\Infrastructure\Models\EloquentCategory;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\EntryItemSerial\Infrastructure\Models\EloquentEntryItemSerial;
use App\Modules\MeasurementUnit\Infrastructure\Models\EloquentMeasurementUnit;
use App\Modules\ReferenceCode\Infrastructure\Models\EloquentReferenceCode;
use App\Modules\SubCategory\Infrastructure\Models\EloquentSubCategory;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentArticle extends Model
{
    protected $table = 'articles';
    protected $fillable = [
        'cod_fab',
        'description',
        'weight',
        'with_deduction',
        'series_enabled',
        'measurement_unit_id',
        'brand_id',
        'category_id',
        'location',
        'warranty',
        'tariff_rate',
        'igv_applicable',
        'plastic_bag_applicable',
        'min_stock',
        'currency_type_id',
        'purchase_price',
        'public_price',
        'distributor_price',
        'authorized_price',
        'public_price_percent',
        'distributor_price_percent',
        'authorized_price_percent',
        'status',
        'user_id',
        'venta',
        'subcategory_id',
        'category_id',
        'sub_category_id',
        'company_type_id',
        'image_url',
        'filt_NameEsp',
        'status_Esp',
        'state_modify_article',
        'url_supplier',
        'article_type_id'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function measurementUnit(): BelongsTo
    {
        return $this->belongsTo(EloquentMeasurementUnit::class, 'measurement_unit_id');
    }
    public function brand(): BelongsTo
    {
        return $this->belongsTo(EloquentBrand::class, 'brand_id');
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(EloquentCategory::class, 'category_id');
    }
    public function currencyType(): BelongsTo
    {
        return $this->belongsTo(EloquentCurrencyType::class, 'currency_type_id');
    }
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(EloquentSubCategory::class, 'sub_category_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(EloquentUser::class, 'user_id');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'company_type_id');
    }
    public function referenceCodes()
    {
        return $this->hasMany(EloquentReferenceCode::class, 'article_id', 'id');
    }
    public function entryItemSerials()
    {
        return $this->hasMany(EloquentEntryItemSerial::class, 'article_id', 'id');
    }

    public function visibleArticles()
    {
        return $this->hasMany(\App\Modules\VisibleArticles\Infrastructure\Models\EloquentVisibleArticle::class, 'article_id', 'id');
    }

    public function toDomain(EloquentArticle $eloquentArticle): Article
    {
        return new Article(
            id: $eloquentArticle->id,
            cod_fab: $eloquentArticle->cod_fab,
            description: $eloquentArticle->description,
            weight: $eloquentArticle->weight,
            with_deduction: $eloquentArticle->with_deduction,
            series_enabled: $eloquentArticle->series_enabled,
            measurementUnit: $eloquentArticle->measurementUnit?->toDomain($eloquentArticle->measurementUnit),
            brand: $eloquentArticle->brand?->toDomain($eloquentArticle->brand),
            category: $eloquentArticle->category->toDomain($eloquentArticle->category),
            subCategory: $eloquentArticle->subCategory?->toDomain($eloquentArticle->subCategory),
            location: $eloquentArticle->location,
            warranty: $eloquentArticle->warranty,
            tariff_rate: $eloquentArticle->tariff_rate,
            igv_applicable: $eloquentArticle->igv_applicable,
            min_stock: $eloquentArticle->min_stock,
            currencyType: $eloquentArticle->currencyType?->toDomain($eloquentArticle->currencyType),
            purchase_price: $eloquentArticle->purchase_price,
            public_price: $eloquentArticle->public_price,
            distributor_price: $eloquentArticle->distributor_price,
            authorized_price: $eloquentArticle->authorized_price,
            public_price_percent: $eloquentArticle->public_price_percent,
            distributor_price_percent: $eloquentArticle->distributor_price_percent,
            authorized_price_percent: $eloquentArticle->authorized_price_percent,
            status: $eloquentArticle->status,
            user: $eloquentArticle->user->toDomain($eloquentArticle->user),
            venta: $eloquentArticle->venta,
            company: $eloquentArticle->company->toDomain($eloquentArticle->company),
            image_url: $eloquentArticle->image_url,
            filtNameEsp: $eloquentArticle->filt_NameEsp,
            statusEsp: $eloquentArticle->status_Esp,
            state_modify_article: $eloquentArticle->state_modify_article,
            article_type_id: $eloquentArticle->article_type_id ?? 1
        );
    }
}
