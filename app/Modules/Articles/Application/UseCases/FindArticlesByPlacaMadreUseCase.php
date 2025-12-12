<?php

namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;

class FindArticlesByPlacaMadreUseCase
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) {}

    public function execute(?string $description, int $branchId)
    {
        return $this->articleRepository->findArticlesByPlacaMadre($description, $branchId);
    }
}