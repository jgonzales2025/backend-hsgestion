<?php

namespace App\Modules\EntryGuideArticle\Domain\Entities;

use App\Modules\Articles\Domain\Entities\Article;

class EntryGuideArticle
{
    private ?int $id;
    private int $entry_guide_id;
    private Article $article;
    private string $description;
    private float $quantity;
    private float $saldo;

    public function __construct(
        ?int $id,
        int $entry_guide_id,
        Article $article,
        string $description,
        float $quantity,
        float $saldo = 0.0,
    ) {
        $this->id = $id;
        $this->entry_guide_id = $entry_guide_id;
        $this->article = $article;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->saldo = $saldo;
    }
    public function getId(): int|null
    {
        return $this->id;
    }
    public function getEntryGuideId(): int
    {
        return $this->entry_guide_id;
    }
    public function getArticle(): Article
    {
        return $this->article;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getQuantity(): float
    {
        return $this->quantity;
    }
    public function getSaldo(): float
    {
        return $this->saldo;
    }
}
