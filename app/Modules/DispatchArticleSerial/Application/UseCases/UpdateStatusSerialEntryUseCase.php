<?php

namespace App\Modules\DispatchArticleSerial\Application\UseCases;

use App\Modules\DispatchArticleSerial\Domain\Interfaces\DispatchArticleSerialRepositoryInterface;

class UpdateStatusSerialEntryUseCase
{
    public function __construct(
        private DispatchArticleSerialRepositoryInterface $dispatchArticleSerialRepository
    ) {
    }

    public function execute(int $branchId, string $serial): void
    {
        $this->dispatchArticleSerialRepository->updateStatusSerialEntry($branchId, $serial);
    }
}