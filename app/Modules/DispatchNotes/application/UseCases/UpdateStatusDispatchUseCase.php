<?php

namespace App\Modules\DispatchNotes\Application\UseCases;

use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;

class UpdateStatusDispatchUseCase
{
    public function __construct(
        private DispatchNotesRepositoryInterface $dispatchNotesRepository
    ) {
    }

    public function execute(int $transferOrderId): void
    {
        $this->dispatchNotesRepository->updateStatusDispatch($transferOrderId);
    }
}