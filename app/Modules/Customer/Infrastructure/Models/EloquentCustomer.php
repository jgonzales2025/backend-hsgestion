<?php

namespace App\Modules\Customer\Infrastructure\Models;

use App\Modules\CustomerType\Infrastructure\Models\EloquentCustomerType;
use App\Modules\RecordType\Infrastructure\Models\EloquentRecordType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentCustomer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'record_type_id',
        'customer_document_type_id',
        'document_number',
        'company_name',
        'name',
        'lastname',
        'second_lastname',
        'customer_type_id',
        'fax',
        'contact',
        'is_withholding_applicable',
        'status'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function customerType(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomerType::class, 'customer_type_id');
    }

    public function recordType(): BelongsTo
    {
        return $this->belongsTo(EloquentRecordType::class, 'record_type_id');
    }
}
