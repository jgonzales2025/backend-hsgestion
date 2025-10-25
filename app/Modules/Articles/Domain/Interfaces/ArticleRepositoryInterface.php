<?php

namespace App\Modules\Articles\Domain\Interfaces;

use App\Modules\Articles\Domain\Entities\Article;
use Illuminate\Http\UploadedFile;

interface ArticleRepositoryInterface
{
    public function save(Article $article): ?Article;
    public function findAllArticle(?string $name): array;
    public function findById(int $id): ?Article;
    public function update(Article $article): void;

}