<?php

namespace App\Modules\Dashboard\Application\UseCases;

use App\Modules\Dashboard\Domain\Interfaces\DashboardRepositoryInterface;

class GetDetailByDocumentsUseCase
{
    public function __construct(private readonly DashboardRepositoryInterface $dashboardRepository){}
    
    public function execute(int $company_id, string $start_date, string $end_date): array
    {
        return $this->dashboardRepository->getDetailByDocuments($company_id, $start_date, $end_date);
    }
}