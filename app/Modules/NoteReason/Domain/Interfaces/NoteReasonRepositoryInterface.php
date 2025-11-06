<?php

namespace App\Modules\NoteReason\Domain\Interfaces;

use App\Modules\NoteReason\Domain\Entities\NoteReason;

interface NoteReasonRepositoryInterface
{
    public function findAll(int $documentTypeId): array;

    public function findById(int $id): ?NoteReason;
}
