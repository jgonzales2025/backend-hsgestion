<?php

namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;

class FindAllArticleUseCase
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }
    public function execute(?string $name, ?int $branchId, ?int $brand_id, ?int $category_id, ?int $status,?string $medida)
    {
        return $this->articleRepository->findAllArticle($name, $branchId, $brand_id, $category_id, $status,$medida);
    }
}