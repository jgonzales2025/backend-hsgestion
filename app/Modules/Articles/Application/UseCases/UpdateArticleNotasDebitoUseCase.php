<?php

namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Application\DTOS\ArticleNotasDebitoDTO;
use App\Modules\Articles\Domain\Entities\ArticleNotasDebito;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;

class  UpdateArticleNotasDebitoUseCase{
    public function __construct(private readonly ArticleRepositoryInterface $articleRepository)
    {
    }

    public function execute(int $id, ArticleNotasDebitoDTO $articleNotasDebitoDTO): ?ArticleNotasDebito
    {
        $articleNotasDebito = new ArticleNotasDebito(
            id: $id,
            user_id: $articleNotasDebitoDTO->user_id,
            company_id: $articleNotasDebitoDTO->company_id,
            filt_NameEsp: $articleNotasDebitoDTO->filt_NameEsp,
            status_Esp: $articleNotasDebitoDTO->status_Esp
        );

        return $this->articleRepository->updateNotesDebito($articleNotasDebito);
    }
}