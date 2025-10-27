<?php

namespace App\Modules\MonthlyClosure\Application\UseCases;

use App\Modules\MonthlyClosure\Application\DTOs\MonthlyClosureDTO;
use App\Modules\MonthlyClosure\Domain\Entities\MonthlyClosure;
use App\Modules\MonthlyClosure\Domain\Interfaces\MonthlyClosureRepositoryInterface;

readonly class CreateMonthlyClosureUseCase
{
    public function __construct(private readonly MonthlyClosureRepositoryInterface $monthlyClosureRepository){}

    public function execute(MonthlyClosureDTO $monthlyClosureDTO): ?MonthlyClosure
    {
        $monthlyClosure = new MonthlyClosure(
            id: 0,
            year: $monthlyClosureDTO->year,
            month: $monthlyClosureDTO->month,
            st_purchases: 1,
            st_sales: 1,
            st_cash: 1,
        );

        return $this->monthlyClosureRepository->save($monthlyClosure);
    }
}
