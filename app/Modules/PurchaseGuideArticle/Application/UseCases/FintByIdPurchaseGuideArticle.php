<?php

namespace App\Modules\PurchaseGuideArticle\Application\UseCases;

use App\Modules\PurchaseGuideArticle\Domain\Entities\PurchaseGuideArticle;
use App\Modules\PurchaseGuideArticle\Domain\Interface\PurchaseGuideArticleRepositoryInterface;

class FintByIdPurchaseGuideArticle{
    public function __construct(private readonly PurchaseGuideArticleRepositoryInterface $purchaseGuideArticleRepositoryInterface){
    }
    public function execute(int $id):?PurchaseGuideArticle{
        return $this->purchaseGuideArticleRepositoryInterface->findById($id);
    }
}