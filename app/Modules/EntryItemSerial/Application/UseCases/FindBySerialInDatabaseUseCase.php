<?php

namespace App\Modules\EntryItemSerial\Application\UseCases;

use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;

class FindBySerialInDatabaseUseCase{
    public function __construct(private EntryItemSerialRepositoryInterface $entryItemSerialRepository){}

    public function execute(string $serial): bool
    {
        return $this->entryItemSerialRepository->findSerialInDatabase($serial);
    }
}