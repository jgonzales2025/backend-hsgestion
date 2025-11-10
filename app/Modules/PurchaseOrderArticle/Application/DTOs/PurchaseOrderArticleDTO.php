<?php

namespace App\Modules\PurchaseOrderArticle\Application\DTOs;

class PurchaseOrderArticleDTO
{
    public int $purchase_order_id;
    public int $article_id;
    public string $description;
    public float $weight;
    public int $quantity;
    public float $purchase_price;
    public float $subtotal;

    public function __construct(array $data)
    {
        $this->purchase_order_id = $data['purchase_order_id'];
        $this->article_id = $data['article_id'];
        $this->description = $data['description'];
        $this->weight = $data['weight'];
        $this->quantity = $data['quantity'];
        $this->purchase_price = $data['purchase_price'];
        $this->subtotal = $data['subtotal'];
    }
}
