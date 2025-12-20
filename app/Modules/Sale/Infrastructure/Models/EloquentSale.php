<?php

namespace App\Modules\Sale\Infrastructure\Models;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;
use App\Modules\NoteReason\Infrastructure\Models\EloquentNoteReason;
use App\Modules\PaymentType\Infrastructure\Models\EloquentPaymentType;
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
        'respuesta_sunat'
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
}
