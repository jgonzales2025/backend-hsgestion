<?php

namespace App\Modules\DocumentEntryGuide\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentDocumentEntryGuide extends Model
{
    protected $table = 'documents_entry_guides_tabla';

    protected $fillable = [
        'entry_guide_id',
        'guide_serie_supplier',
        'guide_correlative_supplier',
        'invoice_serie_supplier',
        'invoice_correlative_supplier',
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
