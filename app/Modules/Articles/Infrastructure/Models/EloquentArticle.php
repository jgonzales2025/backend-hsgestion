<?php

namespace App\Modules\Articles\Infrastructure\Models;


use App\Modules\Brand\Infrastructure\Models\EloquentBrand;
use App\Modules\Category\Infrastructure\Models\EloquentCategory;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\MeasurementUnit\Infrastructure\Models\EloquentMeasurementUnit;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentArticle extends Model{
    protected $table = 'articles';
        protected $fillable = [
        'cod_fab',
        'description',
        'short_description',
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
        'cost_to_price_percent',
        'purchase_price',
        'public_price',
        'distributor_price',
        'authorized_price',
        'public_price_percent',
        'distributor_price_percent',
        'authorized_price_percent',
        'status',
        'user_id',
    ];
     protected $hidden = ['created_at', 'updated_at'];

         public function measurementUnit(): BelongsTo
    {
        return $this->belongsTo(EloquentMeasurementUnit::class, 'measurement_unit_id');
    }

    //  Relación con Marca
    public function brand(): BelongsTo
    {
        return $this->belongsTo(EloquentBrand::class, 'brand_id');
    }

    //  Relación con Categoría
    // public function category(): BelongsTo
    // {
    //     return $this->belongsTo(EloquentCategory::class, 'category_id');
    // }

    // //  Relación con Tipo de Moneda
    // public function currencyType(): BelongsTo
    // {
    //     return $this->belongsTo(EloquentCurrencyType::class, 'currency_type_id');
    // }

    //  Relación con Estado
    // public function statusRelation(): BelongsTo
    // {
    //     return $this->belongsTo(EloquentStatus::class, 'status');
    // }

    //  Relación con Usuario
    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(EloquentUser::class, 'user_id');
    // }

}