<?php

namespace App\Modules\PurchaseItemSerials\Domain\Entities;


class PurchaseItemSerial{
    private ?int $id;
    private int $purchase_guide_id;
    private int $article_id;
    private string $serial;

    public function __construct(
        ?int $id,
        int $purchase_guide_id,
        int $article_id,
        string $serial
    ){
       $this->id = $id;
       $this->purchase_guide_id = $purchase_guide_id;
       $this->article_id = $article_id;
       $this->serial = $serial;
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
    public function getSerial():string{
        return $this->serial;
    }
}