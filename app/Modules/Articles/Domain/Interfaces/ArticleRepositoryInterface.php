<?php

namespace App\Modules\Articles\Domain\Interfaces;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Articles\Domain\Entities\ArticleNotasDebito;

interface ArticleRepositoryInterface
{
  public function save(Article $article): ?Article;
  public function findAllArticle(?string $name, ?int $branchId): array;
  public function findAllArticleNotesDebito(?string $name): array;
  public function findById(int $id): ?Article;
  public function update(Article $article): ?Article;
  public function findAllArticlePriceConvertion(string $date, ?string $description, ?int $articleId, ?int $branchId);
  public function cretaArticleNotasDebito(ArticleNotasDebito $articleNotasDebitoDTO): ?ArticleNotasDebito;
  public function updateNotesDebito(ArticleNotasDebito $article): ?ArticleNotasDebito;
  public function findByIdNotesDebito(int $id): ?ArticleNotasDebito;
  public function requiredSerial(int $articleId): bool;
  public function updateStatus(int $articleId, int $status): void;
}
