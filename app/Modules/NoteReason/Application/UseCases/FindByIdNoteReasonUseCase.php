<?php

namespace App\Modules\NoteReason\Application\UseCases;

use App\Modules\NoteReason\Domain\Entities\NoteReason;
use App\Modules\NoteReason\Domain\Interfaces\NoteReasonRepositoryInterface;

readonly class FindByIdNoteReasonUseCase
{
    public function __construct(private readonly NoteReasonRepositoryInterface $noteReasonRepository){}

    public function execute(?int $id): ?NoteReason
    {
        return $this->noteReasonRepository->findById($id);
    }
}
