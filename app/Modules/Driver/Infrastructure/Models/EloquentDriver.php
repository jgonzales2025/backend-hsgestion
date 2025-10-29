<?php

namespace App\Modules\Driver\Infrastructure\Models;

use App\Models\CustomerDocumentType;
use App\Modules\CustomerDocumentType\Infrastructure\Models\EloquentCustomerDocumentType;
use App\Modules\Driver\Domain\Entities\Driver;
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
        public function toDomain(EloquentDriver $drive): ?Driver
    {
        return new Driver(
            id:$drive->id,
            customer_document_type_id:$drive-> customer_document_type_id,
            doc_number:$drive->doc_number,
            name:$drive->name,
            pat_surname:$drive->pat_surname,
            mat_surname:$drive->mat_surname,
            status:$drive->status,
            license:$drive->license,
            document_type_name:$drive->document_type_name
        );
    }
}
