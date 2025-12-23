<?php

namespace App\Modules\DocumentEntryGuide\Infrastructure\Models;

use App\Modules\DocumentEntryGuide\Domain\Entities\DocumentEntryGuide;
use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;
use App\Modules\EntryGuides\Infrastructure\Models\EloquentEntryGuide;
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

    public function entryGuide()
    {
        return $this->belongsTo(EloquentEntryGuide::class, 'entry_guide_id');
    }
    public function referenceDocument()
    {
        return $this->belongsTo(EloquentDocumentType::class, 'reference_document_id');
    }
    public function toDomain(): DocumentEntryGuide
    {
        return new DocumentEntryGuide(
            id: $this->id,
            entry_guide_id: $this->entry_guide_id,
            reference_document: $this->referenceDocument->toDomain($this->referenceDocument),
            reference_serie: $this->reference_serie,
            reference_correlative: $this->reference_correlative,
        );
    }
}
