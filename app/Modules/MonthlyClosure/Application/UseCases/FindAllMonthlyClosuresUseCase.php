<?php

namespace App\Modules\MonthlyClosure\Application\UseCases;

use App\Modules\MonthlyClosure\Domain\Interfaces\MonthlyClosureRepositoryInterface;

readonly class FindAllMonthlyClosuresUseCase
{
    public function __construct(private readonly MonthlyClosureRepositoryInterface $monthlyClosureRepository){}

    public function execute(): ?array
    {
        return $this->monthlyClosureRepository->findAll();
    }
}
