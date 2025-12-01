<?php

namespace App\Modules\Dashboard\Application\UseCases;

use App\Modules\Dashboard\Domain\Interfaces\DashboardRepositoryInterface;

class GetTopSellingProductsUseCase
{
    public function __construct(
        private DashboardRepositoryInterface $dashboardRepository
    ) {}

    public function execute(int $company_id): array
    {
        return $this->dashboardRepository->getTopSellingProducts($company_id);
    }
}