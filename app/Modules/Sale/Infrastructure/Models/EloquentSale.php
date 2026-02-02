<?php

namespace App\Modules\Sale\Infrastructure\Models;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;
use App\Modules\NoteReason\Infrastructure\Models\EloquentNoteReason;
use App\Modules\PaymentMethod\Infrastructure\Model\EloquentPaymentMethod;
use App\Modules\PaymentType\Infrastructure\Models\EloquentPaymentType;
use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\SaleArticle\Infrastructure\Models\EloquentSaleArticle;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EloquentSale extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'company_id',
        'branch_id',
        'document_type_id',
        'serie',
        'document_number',
        'parallel_rate',
        'customer_id',
        'date',
        'due_date',
        'days',
        'user_id',
        'user_sale_id',
        'payment_type_id',
        'observations',
        'currency_type_id',
        'subtotal',
        'inafecto',
        'igv',
        'total',
        'saldo',
        'amount_amortized',
        'status',
        'payment_status',
        'is_locked',
        'user_authorized_id',
        'reference_document_type_id',
        'reference_serie',
        'reference_correlative',
        'note_reason_id',
        'credit_amount',
        'coddetrac',
        'pordetrac',
        'impdetracs',
        'impdetracd',
        'stretencion',
        'porretencion',
        'impretens',
        'impretend',
        'total_costo_neto',
        'estado_sunat',
        'fecha_aceptacion',
        'respuesta_sunat',
        'consignation_id',
        'fecha_baja_sunat',
        'hora_baja_sunat',
        'igv_percentage'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    /* public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function getDueDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    } */

    public function company(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'company_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(EloquentBranch::class, 'branch_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(EloquentUser::class, 'user_id');
    }

    public function userSale(): BelongsTo
    {
        return $this->belongsTo(EloquentUser::class, 'user_sale_id');
    }

    public function userAuthorized(): BelongsTo
    {
        return $this->belongsTo(EloquentUser::class, 'user_authorized_id');
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(EloquentPaymentType::class, 'payment_type_id');
    }

    public function currencyType(): BelongsTo
    {
        return $this->belongsTo(EloquentCurrencyType::class, 'currency_type_id');
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(EloquentDocumentType::class, 'document_type_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomer::class, 'customer_id');
    }

    public function saleArticles(): HasMany
    {
        return $this->hasMany(EloquentSaleArticle::class, 'sale_id');
    }

    public function noteReason(): BelongsTo
    {
        return $this->belongsTo(EloquentNoteReason::class, 'note_reason_id');
    }
    
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(EloquentPaymentMethod::class, 'payment_method_id');
    }

    public function toDomain(EloquentSale $sale): Sale
    {
        return new Sale(
            id: $sale->id,
            company: $sale->company->toDomain($sale->company),
            branch: $sale->branch->toDomain($sale->branch),
            documentType: $sale->documentType->toDomain($sale->documentType),
            serie: $sale->serie,
            document_number: $sale->document_number,
            parallel_rate: $sale->parallel_rate,
            customer: $sale->customer->toDomain($sale->customer),
            date: $sale->date,
            due_date: $sale->due_date,
            days: $sale->days,
            user: $sale->user->toDomain($sale->user),
            user_sale: $sale->userSale->toDomain($sale->userSale),
            paymentType: $sale->paymentType->toDomain($sale->paymentType),
            observations: $sale->observations,
            currencyType: $sale->currencyType->toDomain($sale->currencyType),
            subtotal: $sale->subtotal,
            igv: $sale->igv,
            total: $sale->total,
            saldo: $sale->saldo,
            amount_amortized: $sale->amount_amortized,
            status: $sale->status,
            payment_status: $sale->payment_status,
            is_locked: $sale->is_locked,
            user_authorized: $sale->userAuthorized?->toDomain($sale->userAuthorized),
            reference_document_type_id: $sale->reference_document_type_id,
            reference_serie: $sale->reference_serie,
            reference_correlative: $sale->reference_correlative,
            credit_amount: $sale->credit_amount,
            coddetrac: $sale->coddetrac,
            pordetrac: $sale->pordetrac,
            impdetracs: $sale->impdetracs,
            impdetracd: $sale->impdetracd,
            stretencion: $sale->stretencion,
            porretencion: $sale->porretencion,
            impretens: $sale->impretens,
            impretend: $sale->impretend,
            total_costo_neto: $sale->total_costo_neto,
            sunat_status: $sale->estado_sunat,
            fecha_aceptacion: $sale->fecha_aceptacion,
            consignation_id: $sale->consignation_id,
            purchase_order: $sale->purchase_order,
            igv_percentage: $sale->igv_percentage
        );
    }

}
