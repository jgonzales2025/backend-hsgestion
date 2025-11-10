<?php

namespace App\Modules\PurchaseOrderArticle\Domain\Entities;

class PurchaseOrderArticle
{
    private int $id;
    private int $purchase_order_id;
    private int $article_id;
    private string $description;
    private float $weight;
    private int $quantity;
    private float $purchase_price;
    private float $subtotal;

    public function __construct(
        int $id,
        int $purchase_order_id,
        int $article_id,
        string $description,
        float $weight,
        int $quantity,
        float $purchase_price,
        float $subtotal
    ) {
        $this->id = $id;
        $this->purchase_order_id = $purchase_order_id;
        $this->article_id = $article_id;
        $this->description = $description;
        $this->weight = $weight;
        $this->quantity = $quantity;
        $this->purchase_price = $purchase_price;
        $this->subtotal = $subtotal;
    }

    public function getId(): int { return $this->id; }
    public function getPurchaseOrderId(): int { return $this->purchase_order_id; }
    public function getArticleId(): int { return $this->article_id; }
    public function getDescription(): string { return $this->description; }
    public function getWeight(): float { return $this->weight; }
    public function getQuantity(): int { return $this->quantity; }
    public function getPurchasePrice(): float { return $this->purchase_price; }
    public function getSubTotal(): float { return $this->subtotal; }
}
