<?php 

namespace App\Modules\VisibleArticles\Application\UseCases;

use App\Modules\VisibleArticles\Domain\Entities\VisibleArticle;
use App\Modules\VisibleArticles\Domain\Interfaces\VisibleArticleRepositoryInterface;

class FindByVisibleArticleUseCase{
     public function __construct(
        private readonly VisibleArticleRepositoryInterface $visibleArticle,
     ){
      
    }
    public function execute(int $id):?VisibleArticle{
        return $this->visibleArticle->findById($id);
    }
}