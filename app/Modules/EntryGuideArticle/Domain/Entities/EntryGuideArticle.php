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
    private float $subtotal;
    private float $total;
    private float $total_descuento;
    private float $descuento;

    public function __construct(
        ?int $id,
        int $entry_guide_id,
        Article $article,
        string $description,
        float $quantity,
        float $saldo = 0.0,
        float $subtotal,
        float $total,
        float $total_descuento ,
        float $descuento ,
    ) {
        $this->id = $id;
        $this->entry_guide_id = $entry_guide_id;
        $this->article = $article;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->saldo = $saldo;
        $this->subtotal = $subtotal;
        $this->total = $total;
        $this->total_descuento = $total_descuento;
        $this->descuento = $descuento;
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
    public function getSubtotal(): float
    {
        return $this->subtotal;
    }
    public function getTotal(): float
    {
        return $this->total;
    }
    public function getTotalDescuento(): float
    {
        return $this->total_descuento;
    }
    public function getDescuento(): float
    {
        return $this->descuento;
    }
}
