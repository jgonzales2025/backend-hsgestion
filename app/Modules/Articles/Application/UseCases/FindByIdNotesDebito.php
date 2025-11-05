<?php

namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Domain\Entities\ArticleNotasDebito;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;

class FindByIdNotesDebito
{
    public function __construct(private readonly ArticleRepositoryInterface $articleRepository)
    {
    }
    public function execute(int $id): ?ArticleNotasDebito
    {
        return $this->articleRepository->findByIdNotesDebito($id);
    }
}