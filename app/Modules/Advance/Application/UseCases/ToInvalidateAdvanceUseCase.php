<?php

namespace App\Modules\Advance\Application\UseCases;

use App\Modules\Advance\Domain\Interfaces\AdvanceRepositoryInterface;

class ToInvalidateAdvanceUseCase
{
    public function __construct(private readonly AdvanceRepositoryInterface $advanceRepository){}

    public function execute(int $id): void
    {
        $this->advanceRepository->toInvalidateAdvance($id);
    }
}