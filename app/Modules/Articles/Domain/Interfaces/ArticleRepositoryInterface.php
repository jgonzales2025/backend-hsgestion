<?php

namespace App\Modules\Articles\Domain\Interfaces;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Articles\Domain\Entities\ArticleNotasDebito;

interface ArticleRepositoryInterface
{
  public function save(Article $article): ?Article;
  public function findAllArticle(?string $name, ?int $branchId, ?int $brand_id, ?int $category_id, ?int $status,?string $medida);
  public function findAllArticleNotesDebito(?string $name);
  public function findById(int $id): ?Article;
  public function update(Article $article): ?Article;
  public function findAllArticlePriceConvertion(string $date, ?string $description, ?int $articleId, ?int $branchId, ?int $priceArticleId);
  public function cretaArticleNotasDebito(ArticleNotasDebito $articleNotasDebitoDTO): ?ArticleNotasDebito;
  public function updateNotesDebito(ArticleNotasDebito $article): ?ArticleNotasDebito;
  public function findByIdNotesDebito(int $id): ?ArticleNotasDebito;
  public function requiredSerial(int $articleId): bool;
  public function updateStatus(int $articleId, int $status): void;
  public function findAllCombos(?string $name): array;
  public function findArticlesByPlacaMadre(?string $description, int $branchId);
}
