<?php

namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Application\DTOS\ArticleNotasDebitoDTO;

use App\Modules\Articles\Domain\Entities\ArticleNotasDebito;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;

class CreateArticleNotasDebito
{
    public function __construct(private readonly ArticleRepositoryInterface $articleRepository)
    {
    }

    public function execute(ArticleNotasDebitoDTO $articleNotasDebitoDTO): ?ArticleNotasDebito
    {
       
        $article = new ArticleNotasDebito(
            id: null,
            user_id: $articleNotasDebitoDTO->user_id,
            company_id: $articleNotasDebitoDTO->company_id,
            filt_NameEsp: $articleNotasDebitoDTO->filt_NameEsp,
            status_Esp: $articleNotasDebitoDTO->status_Esp
        );
     return   $this->articleRepository->cretaArticleNotasDebito($article);
    } 
}