<?php

namespace App\Modules\EntryGuideArticle\Domain\Interface;

use App\Modules\EntryGuideArticle\Domain\Entities\EntryGuideArticle;

interface EntryGuideArticleRepositoryInterface
{

      public function save(EntryGuideArticle $entryGuideArticle): ?EntryGuideArticle;
      public function findAll(): array;
      public function findById(int $id): array;
      public function deleteByEntryGuideId(int $id): void;
      public function findByIdObj(int $entryGuideId): ?EntryGuideArticle;
      public function update(EntryGuideArticle $article): void;
      public function updateQuantity(int $articleId, int $quantity): void;
}
