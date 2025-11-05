<?php

namespace App\Modules\Articles\Infrastructure\Models;


use App\Modules\Brand\Infrastructure\Models\EloquentBrand;
use App\Modules\Category\Infrastructure\Models\EloquentCategory;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\MeasurementUnit\Infrastructure\Models\EloquentMeasurementUnit;
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
        'status_Esp'
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

}