<?php

namespace App\Modules\EntryItemSerial\Application\UseCases;

use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;

class UpdateStatusBySerialsUseCase
{
    public function __construct(private readonly EntryItemSerialRepositoryInterface $entryItemSerialRepository){}

    public function execute(array $serials): void
    {
        $this->entryItemSerialRepository->updateStatusBySerials($serials);
    }
}