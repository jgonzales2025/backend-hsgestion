<?php

namespace App\Modules\Detraction\Application\UseCases;

use App\Modules\Detraction\Domain\Interface\DetractionRepositoryInterface;

class FindAllDetractionsUseCase
{
    public function __construct(
        private DetractionRepositoryInterface $detractionRepository,
    ) {
    }

    public function execute(): array
    {
        return $this->detractionRepository->findAll();
    }
}