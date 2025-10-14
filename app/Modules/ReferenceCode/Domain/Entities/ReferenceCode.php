<?php

namespace App\Modules\ReferenceCode\Domain\Entities;

class ReferenceCode
{
    private int $id;
    private string $refCode;
    private int $articleId;
    private string $dateAt;
    private int $status;

    public function __construct(
        int $id,
        string $refCode,
        int $articleId,
        string $dateAt,
        int $status
    ) {
        $this->id = $id;
        $this->refCode = $refCode;
        $this->articleId = $articleId;
        $this->dateAt = $dateAt;
        $this->status = $status;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getRefCode(): string
    {
        return $this->refCode;
    }

    public function getArticleId(): int
    {
        return $this->articleId;
    }

    public function getDateAt(): string
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
