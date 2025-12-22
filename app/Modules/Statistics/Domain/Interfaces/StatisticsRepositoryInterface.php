<?php

namespace App\Modules\Statistics\Domain\Interfaces;

interface StatisticsRepositoryInterface
{
    public function getCustomerConsumedItems(int $company_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id, ?int $customerId);

    public function getCustomerConsumedItemsPaginated(int $company_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id, ?int $customerId, int $perPage = 15);

    public function getArticlesSold(int $company_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id, ?int $article_id, int $perPage = 15);

    public function getArticleIdSold(int $company_id, int $article_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id);

    public function getArticleIdPurchase(int $company_id, int $article_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id);
}