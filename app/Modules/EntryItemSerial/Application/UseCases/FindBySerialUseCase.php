<?php

namespace App\Modules\PurchaseItemSerials\Application\UseCases;

use App\Modules\EntryItemSerial\Domain\Entities\EntryItemSerial;
use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;

class FindBySerialUseCase
{
    public function __construct(
        private readonly EntryItemSerialRepositoryInterface $entryItemSerialRepositoryInterface
    ) {
    }

    public function execute(string $serial): ?EntryItemSerial
    {
        return $this->entryItemSerialRepositoryInterface->findBySerial($serial);
    }


}
