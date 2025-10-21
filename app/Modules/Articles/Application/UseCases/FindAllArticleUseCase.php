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
    public function execute()
    {
        return $this->articleRepository->findAllArticle();
    }
}