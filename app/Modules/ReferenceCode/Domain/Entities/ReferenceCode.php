<?php

namespace App\Modules\ReferenceCode\Domain\Entities;

class ReferenceCode
{
    private int $id;
    private string $ref_code;
    private int $article_id;
    private ?string $dateAt;
    private int $status;

    public function __construct(
        int $id,
        string $ref_code,
        int $article_id,
        int $status ,
    ) {
        $this->id = $id;
        $this->ref_code = $ref_code;
        $this->article_id = $article_id;
        $this->status = $status;
        $this->dateAt = now(); 
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getRefCode(): string
    {
        return $this->ref_code;
    }

    public function getArticleId(): int
    {
        return $this->article_id;
    }

    public function getDateAt(): ?string
    {
        return $this->dateAt;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    // Setters opcionales
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}
