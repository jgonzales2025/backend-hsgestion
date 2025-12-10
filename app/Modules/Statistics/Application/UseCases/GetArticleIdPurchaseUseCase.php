<?php

namespace App\Modules\Statistics\Application\UseCases;

use App\Modules\Statistics\Domain\Interfaces\StatisticsRepositoryInterface;

class GetArticleIdPurchaseUseCase
{
    public function __construct(
        private readonly StatisticsRepositoryInterface $statisticsRepository
    ) {
    }

    public function execute(int $company_id, int $article_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id)
    {
        return $this->statisticsRepository->getArticleIdPurchase($company_id, $article_id, $branch_id, $start_date, $end_date, $category_id, $brand_id);
    }
}