<?php

namespace App\Modules\Dashboard\Application\UseCases;

use App\Modules\Dashboard\Domain\Interfaces\DashboardRepositoryInterface;

class GetDetailByPaymentMethodsUseCase
{
    public function __construct(private readonly DashboardRepositoryInterface $dashboardRepository){}
    
    public function execute(int $company_id): array
    {
        return $this->dashboardRepository->getDetailByPaymentMethods($company_id);
    }
}