<?php

namespace App\Modules\PurchaseGuideArticle\Domain\Interface;

use App\Modules\PurchaseGuideArticle\Domain\Entities\PurchaseGuideArticle;

interface PurchaseGuideArticleRepositoryInterface{
     
      public function save(PurchaseGuideArticle $purchaseGuideArticle ):?PurchaseGuideArticle;
      public function findAll():array;
      public function findById(int $id):array;
      public function deleteByPurchaseGuideId(int $id):void;

      

}