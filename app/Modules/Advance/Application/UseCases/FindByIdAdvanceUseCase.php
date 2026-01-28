<?php

namespace App\Modules\Advance\Application\UseCases;

use App\Modules\Advance\Domain\Entities\Advance;
use App\Modules\Advance\Domain\Interfaces\AdvanceRepositoryInterface;

class FindByIdAdvanceUseCase
{
    public function __construct(private readonly AdvanceRepositoryInterface $advanceRepository){}

    public function execute(int $id): ?Advance
    {
        return $this->advanceRepository->findById($id);
    }
}