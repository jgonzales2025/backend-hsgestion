<?php

namespace App\Modules\DispatchNotes\Application\UseCases;

use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;

class FindByDocumentUseCase
{
    public function __construct(
        private DispatchNotesRepositoryInterface $dispatchNotesRepository
    ) {
    }

    public function execute(string $serie, string $correlative): ?DispatchNote
    {
        return $this->dispatchNotesRepository->findByDocument($serie, $correlative);
    }
}
