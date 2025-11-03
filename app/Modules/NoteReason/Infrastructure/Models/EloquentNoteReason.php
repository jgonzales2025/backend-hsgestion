<?php

namespace App\Modules\NoteReason\Infrastructure\Models;

use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;
use Illuminate\Database\Eloquent\Model;

class EloquentNoteReason extends Model
{
    protected $table = 'note_reasons';

    protected $fillable = [
        'cod_sunat',
        'description',
        'document_type_id',
        'stock',
        'status'
    ];

    protected $hidden = ['created_at', 'updated_at'];

}
