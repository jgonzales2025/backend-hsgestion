<?php

namespace App\Modules\Driver\Infrastructure\Models;

use App\Models\CustomerDocumentType;
use App\Modules\CustomerDocumentType\Infrastructure\Models\EloquentCustomerDocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentDriver extends Model
{
    protected $table = 'drivers';

    protected $fillable = [
        'customer_document_type_id',
        'doc_number',
        'name',
        'pat_surname',
        'mat_surname',
        'license',
        'status'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function customerDocumentType(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomerDocumentType::class, 'customer_document_type_id');
    }
}
