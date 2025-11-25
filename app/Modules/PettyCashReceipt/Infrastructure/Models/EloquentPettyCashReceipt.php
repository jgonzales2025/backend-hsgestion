<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Models;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;
use App\Modules\PettyCashMotive\Infrastructure\Models\EloquentPettyCashMotive;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentPettyCashReceipt extends Model
{
    protected $table = 'petty_cash_receipt';

    protected $fillable = [
        'company_id',
        'document_type',
        'series',
        'correlative',
        'date',
        'delivered_to',
        'reason_code',
        'currency_type',
        'amount',
        'observation',
        'status',
        'branch_id'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(EloquentBranch::class, 'branch_id');
    }
    public function currency(): BelongsTo
    {
        return $this->belongsTo(EloquentCurrencyType::class, 'currency_type');
    }
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(EloquentDocumentType::class, 'document_type');
    }
    public function reasonCode(): BelongsTo
    {
        return $this->belongsTo(EloquentPettyCashMotive::class, 'reason_code');
    }
}
