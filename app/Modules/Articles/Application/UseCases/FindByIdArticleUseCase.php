<?php

namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;

class FindByIdArticleUseCase{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository){
        $this->articleRepository = $articleRepository;
    }
    public function execute( $id){
       return $this->articleRepository->findById($id);
    }
}