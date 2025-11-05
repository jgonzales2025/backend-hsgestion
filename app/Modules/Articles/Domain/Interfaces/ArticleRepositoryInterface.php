<?php

namespace App\Modules\Articles\Domain\Interfaces;

use App\Modules\Articles\Domain\Entities\Article;
<<<<<<< HEAD
use App\Modules\Articles\Domain\Entities\ArticleNotasDebito;
=======
use Illuminate\Support\Collection;
>>>>>>> 9b62d779dfefdbc0a9cb250fdf97c96ce28e0796
interface ArticleRepositoryInterface
{
    public function save(Article $article): ?Article;
    public function findAllArticle(?string $name): array;
<<<<<<< HEAD
      public function findAllArticleNotesDebito (?string $name): array;
    public function findById(int $id): ?Article;
    public function update(Article $article): ?Article;
    public function findAllArticlePriceConvertion(string $date, ?string $description): array;
    public function cretaArticleNotasDebito(ArticleNotasDebito $articleNotasDebitoDTO): ?ArticleNotasDebito;
    public function updateNotesDebito(ArticleNotasDebito $article): ?ArticleNotasDebito;
     public function findByIdNotesDebito(int $id): ?ArticleNotasDebito;

    
=======
     public function findAllArticleNotasDebito(?string $name): array;
    public function findById(int $id): ?Article;
    public function update(Article $article): ?Article;
    public function findAllArticlePriceConvertion(string $date, ?string $description): array;
    public function findAllExcel(?string $name): Collection;
>>>>>>> 9b62d779dfefdbc0a9cb250fdf97c96ce28e0796

}
