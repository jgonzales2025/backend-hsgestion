<?php

namespace App\Modules\PurchaseGuideArticle\Application\DTOS;

class PurchaseGuideArticleDTO{
    public $purchase_guide_id;
    public  $article_id;
    public  $description;
    public  $quantity;

    function __construct($array){
        $this->purchase_guide_id = $array['purchase_guide_id'];
        $this->article_id = $array['article_id'];
        $this->description = $array['description'];
        $this->quantity = $array['quantity'];
    }
}