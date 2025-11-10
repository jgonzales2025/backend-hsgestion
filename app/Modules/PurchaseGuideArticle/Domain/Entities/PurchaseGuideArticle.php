<?php

namespace App\Modules\PurchaseGuideArticle\Domain\Entities;

class PurchaseGuideArticle{
    private ?int $id;
    private int $purchase_guide_id;
    private int $article_id;
    private string $description;
    private float $quantity;

    public function __construct(
        ?int $id,
        int $purchase_guide_id,
        int $article_id,
        string $description,
        float $quantity,
    ){
        $this->id = $id;
        $this->purchase_guide_id = $purchase_guide_id;
        $this->article_id = $article_id;
        $this->description = $description;
        $this->quantity = $quantity;
    }
    public function getId():int|null{
        return $this->id;
    }
    public function getPurchaseGuideId():int{
        return $this->purchase_guide_id;
    }
    public function getArticleId():int{
        return $this->article_id;
    }
    public function getDescription():string{
        return $this->description;
    }
    public function getQuantity():float{
        return $this->quantity;
    }
    

}