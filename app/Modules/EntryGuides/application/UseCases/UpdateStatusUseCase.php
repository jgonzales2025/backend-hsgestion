<?php

namespace App\Modules\EntryGuides\Application\UseCases;

use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;

class UpdateStatusUseCase
{
    public function __construct(
        private readonly EntryGuideRepositoryInterface $entryGuideRepositoryInterface,
    ) {}

    public function execute(int $id, int $status): void
    {
        $this->entryGuideRepositoryInterface->updateStatus($id, $status);
    }
}