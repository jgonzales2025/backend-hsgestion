<?php

namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;

class FindAllArticlesNotesDebitoUseCase
{
  

    public function __construct(private readonly ArticleRepositoryInterface $articleRepository)
    {
    
    }

    public function execute(): array
    {
        return $this->articleRepository->findAllArticleNotesDebito(null);
    }
}