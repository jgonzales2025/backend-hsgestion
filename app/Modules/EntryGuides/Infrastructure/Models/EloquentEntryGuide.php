<?php

namespace App\Modules\EntryGuides\Infrastructure\Models;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use App\Modules\IngressReason\Infrastructure\Models\EloquentIngressReason;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'status'
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
            reference_po_serie: $eloquentEntryGuide->reference_serie,
            reference_po_correlative: $eloquentEntryGuide->reference_correlative,
            status: $eloquentEntryGuide->status
        );
    }


}