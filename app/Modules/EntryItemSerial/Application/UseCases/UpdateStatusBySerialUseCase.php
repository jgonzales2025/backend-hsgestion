<?php

namespace App\Modules\EntryItemSerial\Application\UseCases;

use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;

class UpdateStatusBySerialUseCase
{
    public function __construct(private EntryItemSerialRepositoryInterface $repository)
    {
    }

    public function execute(string $serial, int $status): void
    {
        $this->repository->updateStatusBySerial($serial, $status);
    }
}