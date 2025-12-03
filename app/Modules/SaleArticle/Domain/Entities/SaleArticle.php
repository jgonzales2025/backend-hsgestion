<?php

namespace App\Modules\SaleArticle\Domain\Entities;

use App\Modules\Articles\Domain\Entities\Article;

class SaleArticle
{
    private int $id;
    private int $sale_id;
    private ?string $sku;
    private ?int $state_modify_article;
    private Article $article;
    private ?string $description;
    private int $quantity;
    private float $unit_price;
    private float $public_price;
    private float $subtotal;
    private ?bool $series_enabled;
    private float $purchase_price;
    private float $costo_neto;

    public function __construct(int $id, int $sale_id, ?string $sku, Article $article, ?string $description, int $quantity, float $unit_price, float $public_price, float $subtotal, float $purchase_price, float $costo_neto, ?int $state_modify_article = null, ?bool $series_enabled = null)
    {
        $this->id = $id;
        $this->sale_id = $sale_id;
        $this->sku = $sku;
        $this->article = $article;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->unit_price = $unit_price;
        $this->public_price = $public_price;
        $this->subtotal = $subtotal;
        $this->state_modify_article = $state_modify_article;
        $this->purchase_price = $purchase_price;
        $this->costo_neto = $costo_neto;
        $this->series_enabled = $series_enabled;
    }

    public function getId(): int { return $this->id; }
    public function getSaleId(): int { return $this->sale_id; }
    public function getSku(): ?string { return $this->sku; }
    public function getStateModifyArticle(): ?int { return $this->state_modify_article; }
    public function getArticle(): Article { return $this->article; }
    public function getDescription(): ?string { return $this->description; }
    public function getQuantity(): int { return $this->quantity; }
    public function getUnitPrice(): float { return $this->unit_price; }
    public function getPublicPrice(): float { return $this->public_price; }
    public function getSubtotal(): float { return $this->subtotal; }
    public function getSeriesEnabled(): ?bool { return $this->series_enabled; }
    public function getPurchasePrice(): float { return $this->purchase_price; }
    public function getCostoNeto(): float { return $this->costo_neto; }
}
