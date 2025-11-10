<?php

namespace App\Modules\PurchaseItemSerials\Application\DTOS;

class PurchaseItemSerialDTO{
    public  $purchase_guide_id;
    public  $article_id;
    public  $serial;

    public function __construct($array){
        $this->purchase_guide_id = $array['purchase_guide_id'];
        $this->article_id = $array['article_id'];
        $this->serial = $array['serial'];
    }


}