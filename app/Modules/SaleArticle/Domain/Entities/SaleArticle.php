<?php

namespace App\Modules\SaleArticle\Domain\Entities;

class SaleArticle
{
    private int $id;
    private int $sale_id;
    private int $article_id;
    private ?string $description;
    private int $quantity;
    private float $unit_price;
    private float $public_price;
    private float $subtotal;

    public function __construct(int $id, int $sale_id, int $article_id, ?string $description, int $quantity, float $unit_price, float $public_price, float $subtotal)
    {
        $this->id = $id;
        $this->sale_id = $sale_id;
        $this->article_id = $article_id;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->unit_price = $unit_price;
        $this->public_price = $public_price;
        $this->subtotal = $subtotal;
    }

    public function getId(): int { return $this->id; }
    public function getSaleId(): int { return $this->sale_id; }
    public function getArticleId(): int { return $this->article_id; }
    public function getDescription(): ?string { return $this->description; }
    public function getQuantity(): int { return $this->quantity; }
    public function getUnitPrice(): float { return $this->unit_price; }
    public function getPublicPrice(): float { return $this->public_price; }
    public function getSubtotal(): float { return $this->subtotal; }
}
