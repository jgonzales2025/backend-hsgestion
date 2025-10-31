<?php

namespace App\Modules\NoteReason\Domain\Interfaces;

interface NoteReasonRepositoryInterface
{
    public function findAll(int $documentTypeId): array;
}
