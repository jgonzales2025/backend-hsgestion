<?php

namespace App\Modules\Purchases\Infrastructure\Models;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\DetailPurchaseGuides\Infrastructure\Models\EloquentDetailPurchaseGuide;
use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;
use App\Modules\PaymentMethod\Infrastructure\Model\EloquentPaymentMethod;
use App\Modules\PaymentType\Infrastructure\Models\EloquentPaymentType;
use App\Modules\Purchases\Domain\Entities\Purchase;
use App\Modules\ShoppingIncomeGuide\Infrastructure\Models\EloquentShoppingIncomeGuide;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EloquentPurchase extends Model
{
    protected $table = 'purchase';

    protected $fillable = [
        'company_id',
        'branch_id',
        'supplier_id',
        'serie',
        'correlative',
        'exchange_type',
        'payment_type_id',
        'currency',
        'date',
        'date_ven',
        'days',
        'observation',
        'detraccion',
        'fech_detraccion',
        'amount_detraccion',
        'is_detracion',
        'subtotal',
        'total_desc',
        'inafecto',
        'igv',
        'total',
        'saldo',
        'is_igv',
        'document_type_id',
        'reference_serie',
        'reference_correlative',
        'saldo',
        'nc_document_id',
        'nc_reference_serie',
        'nc_reference_correlative',
        'status',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(EloquentPaymentType::class, 'payment_type_id');
    }

    public function branches(): BelongsTo
    {
        return $this->belongsTo(EloquentBranch::class, 'branch_id');
    }

    public function customers(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomer::class, 'supplier_id');
    }

    public function currencyType(): BelongsTo
    {
        return $this->belongsTo(EloquentCurrencyType::class, 'currency');
    }
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(EloquentDocumentType::class, 'document_type_id');
    }
    public function detComprasGuiaIngreso(): HasMany
    {
        return $this->hasMany(EloquentDetailPurchaseGuide::class, 'purchase_id');
    }
    public function shoppingIncomeGuide(): HasMany
    {
        return $this->hasMany(EloquentShoppingIncomeGuide::class, 'purchase_id');
    }
    public function toDomain(): Purchase
    {
        return new Purchase(
            id: $this->id,
            company_id: $this->company_id,
            branch: $this->branches?->toDomain($this->branches),
            supplier: $this->customers?->toDomain($this->customers),
            serie: $this->serie,
            correlative: $this->correlative,
            exchange_type: $this->exchange_type,
            payment_type: $this->paymentType?->toDomain($this->paymentType),
            currency: $this->currencyType?->toDomain($this->currencyType),
            date: $this->date,
            date_ven: $this->date_ven,
            days: $this->days,
            observation: $this->observation,
            detraccion: $this->detraccion,
            fech_detraccion: $this->fech_detraccion,
            amount_detraccion: $this->amount_detraccion,
            is_detracion: $this->is_detracion,
            subtotal: $this->subtotal,
            total_desc: $this->total_desc,
            inafecto: $this->inafecto,
            igv: $this->igv,
            total: $this->total,
            saldo: $this->saldo,
            is_igv: $this->is_igv,
            type_document_id: $this->documentType?->toDomain($this->documentType),
            reference_serie: $this->reference_serie,
            reference_correlative: $this->reference_correlative,
            det_compras_guia_ingreso: $this->detComprasGuiaIngreso?->map(fn($d) => $d->toDomain())->toArray() ?? [],
            shopping_Income_Guide: $this->shoppingIncomeGuide?->map(fn($d) => $d->toDomain())->toArray() ?? [],
            nc_document_id: $this->nc_document_id,
            nc_reference_serie: $this->nc_reference_serie,
            nc_reference_correlative: $this->nc_reference_correlative,
            status: $this->status,
        );
    }
}
