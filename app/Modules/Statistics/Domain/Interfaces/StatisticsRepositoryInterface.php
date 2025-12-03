<?php

namespace App\Modules\Statistics\Domain\Interfaces;

interface StatisticsRepositoryInterface
{
    public function getCustomerConsumedItems(int $company_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id, ?int $customerId);
}