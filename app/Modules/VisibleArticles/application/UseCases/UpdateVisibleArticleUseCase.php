<?php

namespace App\Modules\VisibleArticles\Application\UseCases;

use App\Modules\VisibleArticles\Application\DTOS\VisibleArticleDTO;
use App\Modules\VisibleArticles\Domain\Entities\VisibleArticle;
use App\Modules\VisibleArticles\Domain\Interfaces\VisibleArticleRepositoryInterface;

class UpdateVisibleArticleUseCase
{
   public function __construct(private readonly VisibleArticleRepositoryInterface $visibleArticle)
   {
   }
   public function execute(int $id, VisibleArticleDTO $visibleArticle): void
   {

      $existeType = new VisibleArticle(
         id: $id,
         company_id: $visibleArticle->company_id,
         branch_id: $visibleArticle->branch_id,
         article_id: $visibleArticle->article_id,
         user_id: $visibleArticle->user_id,
         status: $visibleArticle->status
      );
      $this->visibleArticle->update($existeType);
   }
}