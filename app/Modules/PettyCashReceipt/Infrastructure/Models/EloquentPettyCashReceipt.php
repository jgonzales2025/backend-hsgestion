<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Models;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentPettyCashReceipt extends Model{
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
    'created_by',
    'created_at_manual',
    'updated_by',
    'updated_at_manual',
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
}