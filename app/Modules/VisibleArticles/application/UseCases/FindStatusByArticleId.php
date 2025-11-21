<?php

namespace App\Modules\VisibleArticles\application\UseCases;

use App\Modules\VisibleArticles\Domain\Interfaces\VisibleArticleRepositoryInterface;

class FindStatusByArticleId
{
    public function __construct(
        private readonly VisibleArticleRepositoryInterface $visibleArticleRepository
    ) {
    }

    public function execute(int $articleId, int $branchId): ?int
    {
        return $this->visibleArticleRepository->findStatusByArticleId($articleId, $branchId);
    }
}