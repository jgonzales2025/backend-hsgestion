<?php

namespace App\Modules\MonthlyClosure\Application\UseCases;

use App\Modules\MonthlyClosure\Domain\Interfaces\MonthlyClosureRepositoryInterface;

readonly class UpdateStSalesUseCase
{
    public function __construct(private readonly MonthlyClosureRepositoryInterface $monthlyClosureRepository){}

    public function execute(int $id, int $status): void
    {
        $this->monthlyClosureRepository->updateStSales($id, $status);
    }
}
