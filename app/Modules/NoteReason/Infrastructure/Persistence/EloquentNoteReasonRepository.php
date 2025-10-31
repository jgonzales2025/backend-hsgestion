<?php

namespace App\Modules\NoteReason\Infrastructure\Persistence;

use App\Modules\NoteReason\Domain\Entities\NoteReason;
use App\Modules\NoteReason\Domain\Interfaces\NoteReasonRepositoryInterface;
use App\Modules\NoteReason\Infrastructure\Models\EloquentNoteReason;

class EloquentNoteReasonRepository implements NoteReasonRepositoryInterface
{

    public function findAll(int $documentTypeId): array
    {
        $noteReasons = EloquentNoteReason::where('document_type_id', $documentTypeId)->get();

        return $noteReasons->map(function ($noteReason) {
            return new NoteReason(
                id: $noteReason->id,
                cod_sunat: $noteReason->cod_sunat,
                description: $noteReason->description,
                document_type_id: $noteReason->document_type_id,
                stock: $noteReason->stock,
                status: $noteReason->status
            );
        })->toArray();
    }
}
