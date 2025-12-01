<?php

namespace App\Modules\Dashboard\Application\UseCases;

use App\Modules\Dashboard\Domain\Interfaces\DashboardRepositoryInterface;

class GetSalesPurchasesAndUtilityUseCase
{
    public function __construct(private DashboardRepositoryInterface $dashboardRepository)
    {
    }

    public function execute(int $company_id, string $start_date, string $end_date): array
    {
        return $this->dashboardRepository->getSalesPurchasesAndUtility($company_id, $start_date, $end_date);
    }
}
