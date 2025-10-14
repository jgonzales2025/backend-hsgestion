<?php

namespace App\Modules\RecordType\Infrastructure\Models;
use App\Models\CustomerDocumentType;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EloquentRecordType extends Model{
    protected $table =  'record_types';
    protected $fillable = [
        'name',
        'abbreviation',
        'status'
    ];
    protected $hidden = ['created_at', 'update_at'];
    public function customerDocumentType():BelongsTo{
        return $this->belongsTo(CustomerDocumentType::class, 'customer_document_type_id');
    }

    public function customers(): HasMany
    {
        return $this->hasMany(EloquentCustomer::class, 'record_type_id');
    }
}
