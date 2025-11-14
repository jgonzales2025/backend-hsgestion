<?php

namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;

class UpdateStatusArticleUseCase
{
    public function __construct(private ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function execute(int $articleId, int $status): void
    {
        $this->articleRepository->updateStatus($articleId, $status);
    }
}