<?php

namespace App\Modules\EntryItemSerial\Application\UseCases;

use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;

class FindBranchBySerial
{
    public function __construct(
        private readonly EntryItemSerialRepositoryInterface $entryItemSerialRepository,
    ) {
    }
    public function execute(string $serial): ?array
    {
        return $this->entryItemSerialRepository->findBranchBySerial($serial);
    }
}
