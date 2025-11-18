<?php

namespace App\Modules\DispatchArticle\Application\DTOS;

class DispatchArticleDTO
{

    public int $dispatch_id;
    public int $article_id;
    public float $quantity;
    public ?float $weight;
    public ?float $saldo;
    public string $name;
    public ?float $subtotal_weight;

    public function __construct(array $data)
    {
        $this->dispatch_id = $data['dispatch_id'];
        $this->article_id = $data['article_id'];
        $this->quantity = $data['quantity'];
        $this->weight = $data['weight'] ?? null;
        $this->saldo = $data['saldo'] ?? null;
        $this->name = $data['name'];
        $this->subtotal_weight = $data['subtotal_weight'] ?? null;

    }
}