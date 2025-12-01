<?php

namespace App\Modules\Dashboard\Application\UseCases;

use App\Modules\Dashboard\Domain\Interfaces\DashboardRepositoryInterface;

class countProductsSoldByCategoryUseCase
{
    public function __construct(
        private DashboardRepositoryInterface $dashboardRepository
    ) {}

    public function execute(int $company_id): array
    {
        return $this->dashboardRepository->countProductsSoldByCategory($company_id);
    }
}
