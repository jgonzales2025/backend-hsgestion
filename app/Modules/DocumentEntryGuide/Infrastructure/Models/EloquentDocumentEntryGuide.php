<?php

namespace App\Modules\DocumentEntryGuide\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentDocumentEntryGuide extends Model
{
    protected $table = 'documents_entry_guides_tabla';

    protected $fillable = [
        'entry_guide_id',
        'reference_document_id',
        'reference_serie',
        'reference_correlative',
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
