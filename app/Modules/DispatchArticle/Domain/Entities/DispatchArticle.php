<?php

namespace App\Modules\DispatchArticle\Domain\Entities;


class DispatchArticle
{
    private ?int $id;
    private int $dispatch_id;
    private int $article_id;
    private float $quantity;
    private float $weight;
    private float $saldo;
    private string $name;
    private float $subtotal_weight;

    public function __construct(
        ?int $id,
        int $dispatch_id,
        int $article_id,
        float $quantity,
        float $weight,
        float $saldo,
        string $name,
        float $subtotal_weight,

    ) {
        $this->id = $id;
        $this->dispatch_id = $dispatch_id;
        $this->article_id = $article_id;
        $this->quantity = $quantity;
        $this->weight = $weight;
        $this->saldo = $saldo;
        $this->name = $name;
        $this->subtotal_weight = $subtotal_weight;

    }
    public function getId():int|null{
        return $this->id;
    }
     public function getDispatchID():int{
        return $this->dispatch_id;
    }
     public function getArticleID():int{
        return $this->article_id;
    }
     public function getQuantity():float{
        return $this->quantity;
    }
     public function getWeight():float{
        return $this->weight;
    }
     public function getSaldo():float{
        return $this->saldo;
    }
     public function getName():string{
        return $this->name;
    }
     public function getsubTotalWeight():float{
        return $this->subtotal_weight;
    }
}