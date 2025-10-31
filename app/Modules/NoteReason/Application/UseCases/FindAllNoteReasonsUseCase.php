<?php

namespace App\Modules\NoteReason\Application\UseCases;

use App\Modules\NoteReason\Domain\Interfaces\NoteReasonRepositoryInterface;

readonly class FindAllNoteReasonsUseCase
{
    public function __construct(private readonly NoteReasonRepositoryInterface $noteReasonRepository){}

    public function execute(int $documentTypeId): array
    {
        return $this->noteReasonRepository->findAll($documentTypeId);
    }
}
