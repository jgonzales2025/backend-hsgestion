<?php

namespace App\Modules\Dashboard\Application\UseCases;

use App\Modules\Dashboard\Domain\Interfaces\DashboardRepositoryInterface;

class GetDetailByDocumentsUseCase
{
    public function __construct(private readonly DashboardRepositoryInterface $dashboardRepository){}
    
    public function execute(int $company_id): array
    {
        return $this->dashboardRepository->getDetailByDocuments($company_id);
    }
}