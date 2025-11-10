<?php

namespace App\Modules\PurchaseGuideArticle\Application\UseCases;

use App\Modules\PurchaseGuideArticle\Application\DTOS\PurchaseGuideArticleDTO;
use App\Modules\PurchaseGuideArticle\Domain\Entities\PurchaseGuideArticle;
use App\Modules\PurchaseGuideArticle\Domain\Interface\PurchaseGuideArticleRepositoryInterface;

class CreatePurchaseGuideArticle{
    
    public function __construct(private readonly PurchaseGuideArticleRepositoryInterface $purchaseGuideArticleRepositoryInterface){}
    public function execute(PurchaseGuideArticleDTO $purchaseGuideArticleDTO):?PurchaseGuideArticle{
       
        $purchaseGuideArticle = new PurchaseGuideArticle(
            id:null,
            purchase_guide_id: $purchaseGuideArticleDTO->purchase_guide_id,
            article_id: $purchaseGuideArticleDTO->article_id,
            description: $purchaseGuideArticleDTO->description,
            quantity: $purchaseGuideArticleDTO->quantity,
        );
        return $this->purchaseGuideArticleRepositoryInterface->save($purchaseGuideArticle);
    
    }

}