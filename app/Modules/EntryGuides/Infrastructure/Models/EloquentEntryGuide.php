<?php

namespace App\Modules\EntryGuides\Infrastructure\Models;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
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
        'correlativo',
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

}