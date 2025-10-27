<?php

namespace App\Modules\MonthlyClosure\Application\UseCases;

use App\Modules\MonthlyClosure\Domain\Entities\MonthlyClosure;
use App\Modules\MonthlyClosure\Domain\Interfaces\MonthlyClosureRepositoryInterface;

readonly class FindByIdMonthlyClosureUseCase
{
    public function __construct(private readonly MonthlyClosureRepositoryInterface $monthlyClosureRepository){}

    public function execute(int $id): ?MonthlyClosure
    {
        return $this->monthlyClosureRepository->findById($id);
    }
}
