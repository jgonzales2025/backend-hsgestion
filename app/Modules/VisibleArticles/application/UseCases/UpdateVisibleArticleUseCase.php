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
         company_id: null,
         branch_id: null,
         article_id: null,
         user_id: null,
         status: $visibleArticle->status
      );
      $this->visibleArticle->update($existeType);
   }
}