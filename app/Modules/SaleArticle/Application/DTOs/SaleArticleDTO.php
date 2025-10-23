<?php

namespace App\Modules\SaleArticle\Application\DTOs;

class SaleArticleDTO
{
    public $sale_id;
    public $article_id;
    public $description;
    public $quantity;
    public $unit_price;
    public $public_price;
    public $subtotal;

    public function __construct(array $data)
    {
        $this->sale_id = $data['sale_id'];
        $this->article_id = $data['article_id'];
        $this->description = $data['description'];
        $this->quantity = $data['quantity'];
        $this->unit_price = $data['unit_price'];
        $this->public_price = $data['public_price'];
        $this->subtotal = $data['subtotal'];
    }
}
