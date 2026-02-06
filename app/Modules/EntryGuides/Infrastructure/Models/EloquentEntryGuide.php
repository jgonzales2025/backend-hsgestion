<?php

namespace App\Modules\EntryGuides\Infrastructure\Models;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use App\Modules\IngressReason\Infrastructure\Models\EloquentIngressReason;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\DocumentEntryGuide\Infrastructure\Models\EloquentDocumentEntryGuide;
use App\Modules\EntryGuideArticle\Infrastructure\Models\EloquentEntryGuideArticle;
use App\Modules\PaymentType\Infrastructure\Models\EloquentPaymentType;

class EloquentEntryGuide extends Model
{
    protected $table = 'entry_guides';

    protected $fillable = [
        'cia_id',
        'branch_id',
        'serie',
        'correlative',
        'date',
        'customer_id',
        'guide_serie_supplier',
        'guide_correlative_supplier',
        'invoice_serie_supplier',
        'invoice_correlative_supplier',
        'observations',
        'ingress_reason_id',
        'reference_serie',
        'reference_correlative',
        'status',
        'subtotal',
        'total_descuento',
        'total',
        'update_price',
        'includ_igv',
        'entry_igv',
        'currency_id',
        'reference_document_id',
        'nc_document_id',
        'nc_reference_serie',
        'nc_reference_correlative',
        'payment_type_id',
        'days',
        'date_ven'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function getDateAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : null;
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(EloquentBranch::class, 'branch_id');
    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomer::class, 'customer_id');
    }
    public function ingressReason(): BelongsTo
    {
        return $this->belongsTo(EloquentIngressReason::class, 'ingress_reason_id');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'cia_id');
    }
    public function documentEntryGuides(): hasMany
    {
        return $this->hasMany(EloquentDocumentEntryGuide::class, 'entry_guide_id');
    }
    public function currency(): BelongsTo
    {
        return $this->belongsTo(EloquentCurrencyType::class, 'currency_id');
    }

    public function payment_type(): BelongsTo
    {
        return $this->belongsTo(EloquentPaymentType::class, 'payment_type_id');
    }

    public function entryGuideArticles(): HasMany
    {
        return $this->hasMany(EloquentEntryGuideArticle::class, 'entry_guide_id');
    }

    public function toDomain(EloquentEntryGuide $eloquentEntryGuide): EntryGuide
    {
        return new EntryGuide(
            id: $eloquentEntryGuide->id,
            cia: $eloquentEntryGuide->company->toDomain($eloquentEntryGuide->company),
            branch: $eloquentEntryGuide->branch->toDomain($eloquentEntryGuide->branch),
            serie: $eloquentEntryGuide->serie,
            correlative: $eloquentEntryGuide->correlative,
            date: $eloquentEntryGuide->date,
            customer: $eloquentEntryGuide->customer->toDomain($eloquentEntryGuide->customer),
            observations: $eloquentEntryGuide->observations,
            ingressReason: $eloquentEntryGuide->ingressReason->toDomain($eloquentEntryGuide->ingressReason),
            reference_serie: $eloquentEntryGuide->reference_serie,
            reference_correlative: $eloquentEntryGuide->reference_correlative,
            status: $eloquentEntryGuide->status,
            subtotal: $eloquentEntryGuide->subtotal,
            total_descuento: $eloquentEntryGuide->total_descuento,
            total: $eloquentEntryGuide->total,
            update_price: (bool) $eloquentEntryGuide->update_price,
            includ_igv: (bool) $eloquentEntryGuide->includ_igv,
            entry_igv: $eloquentEntryGuide->entry_igv,
            currency: $eloquentEntryGuide->currency->toDomain($eloquentEntryGuide->currency),
            reference_document_id: $eloquentEntryGuide->reference_document_id,
            nc_document_id: $eloquentEntryGuide->nc_document_id,
            nc_reference_serie: $eloquentEntryGuide->nc_reference_serie,
            nc_reference_correlative: $eloquentEntryGuide->nc_reference_correlative,
            payment_type: $eloquentEntryGuide->payment_type?->toDomain($eloquentEntryGuide->payment_type),
            days: $eloquentEntryGuide->days,
            date_ven: $eloquentEntryGuide->date_ven,
        );
    }
}
