<?php

namespace App\Modules\NoteReason\Infrastructure\Models;

use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;
use App\Modules\NoteReason\Domain\Entities\NoteReason;
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

    public function toDomain(EloquentNoteReason $eloquentNoteReason): ?NoteReason
    {
        return new NoteReason(
            id: $eloquentNoteReason->id,
            cod_sunat: $eloquentNoteReason->cod_sunat,
            description: $eloquentNoteReason->description,
            document_type_id: $eloquentNoteReason->document_type_id,
            stock: $eloquentNoteReason->stock,
            status: $eloquentNoteReason->status
        );
    }

}
