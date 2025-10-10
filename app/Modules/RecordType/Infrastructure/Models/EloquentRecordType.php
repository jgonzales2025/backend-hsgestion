<?php

namespace App\Modules\RecordType\Infrastructure\Models;
use App\Models\CustomerDocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}