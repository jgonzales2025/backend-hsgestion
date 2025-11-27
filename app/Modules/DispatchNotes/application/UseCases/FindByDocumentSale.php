<?php

namespace App\Modules\DispatchNotes\Application\UseCases;

use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;

class FindByDocumentSale
{
    public function __construct(
        private readonly DispatchNotesRepositoryInterface $dispatchNotesRepository
    ) {
    }

    public function execute(string $serie, string $correlative): ?DispatchNote
    {
        return $this->dispatchNotesRepository->findByDocumentSale($serie, $correlative);
    }
}