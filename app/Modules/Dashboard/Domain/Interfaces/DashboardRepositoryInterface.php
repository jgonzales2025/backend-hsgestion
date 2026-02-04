<?php

namespace App\Modules\Dashboard\Domain\Interfaces;

interface DashboardRepositoryInterface
{
    public function countProductsSoldByCategory(int $company_id): array;
    public function getTopSellingProducts(int $company_id): array;
    public function getSalesPurchasesAndUtility(int $company_id, string $start_date, string $end_date): array;
    public function getTopCustomers(int $company_id): array;
    public function getDetailByDocuments(int $company_id): array;
    public function getDetailByPaymentMethods(int $company_id): array;
}
