<?php

namespace App\Modules\EntryItemSerial\Application\UseCases;

use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;

class FindByEntryGuideBySerialUseCase
{
    public function __construct(
        private EntryItemSerialRepositoryInterface $entryItemSerialRepository
    ) {
    }

    public function execute(string $serial): ?EntryGuide
    {
        return $this->entryItemSerialRepository->findEntryGuideBySerial($serial);
    }
}