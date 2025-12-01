<?php
namespace App\Modules\DetailPcCompatible\Domain\Entities;

class DetailPcCompatible
{
   private ?int $id;
   private int $article_major_id;
   private int $article_accesory_id;
   private bool $status;

    public function __construct(
         ?int $id,
         int $article_major_id,
         int $article_accesory_id,
         bool $status
    ) {
        $this->id = $id;
        $this->article_major_id = $article_major_id;
        $this->article_accesory_id = $article_accesory_id;
        $this->status = $status;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getArticleMajorId(): int
    {
        return $this->article_major_id;
    }

    public function getArticleAccesoryId(): int
    {
        return $this->article_accesory_id;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }
}