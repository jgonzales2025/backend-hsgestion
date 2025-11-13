<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

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
];
      protected $hidden = ['created_at', 'updated_at'];

}