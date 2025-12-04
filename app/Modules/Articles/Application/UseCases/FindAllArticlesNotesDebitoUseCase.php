<?php

namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;

class FindAllArticlesNotesDebitoUseCase
{
  

    public function __construct(private readonly ArticleRepositoryInterface $articleRepository)
    {
    
    }

    public function execute(?string $description)
    {
        return $this->articleRepository->findAllArticleNotesDebito($description);
    }
}