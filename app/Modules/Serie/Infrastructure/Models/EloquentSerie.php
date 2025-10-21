<?php

namespace App\Modules\Serie\Infrastructure\Models;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentSerie extends Model
{
    protected $table = 'series';

    protected $fillable = ['company_id', 'serie_number', 'branch_id', 'elec_document_type_id', 'dir_document_type_id', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'company_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(EloquentBranch::class, 'branch_id');
    }

    public function elecDocumentType(): BelongsTo
    {
        return $this->belongsTo(EloquentDocumentType::class, 'elec_document_type_id');
    }

    public function dirDocumentType(): BelongsTo
    {
        return $this->belongsTo(EloquentDocumentType::class, 'dir_document_type_id');
    }
}
