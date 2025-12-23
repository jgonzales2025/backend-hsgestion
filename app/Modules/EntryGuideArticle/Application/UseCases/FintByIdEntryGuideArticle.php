<?php

namespace App\Modules\PurchaseGuideArticle\Application\UseCases;

use App\Modules\EntryGuideArticle\Domain\Interface\EntryGuideArticleRepositoryInterface as InterfaceEntryGuideArticleRepositoryInterface;
use App\Modules\PurchaseGuideArticle\Domain\Entities\EntryGuideArticle;
use App\Modules\PurchaseGuideArticle\Domain\Interface\EntryGuideArticleRepositoryInterface;

class FintByIdEntryGuideArticle{
    public function __construct(private readonly InterfaceEntryGuideArticleRepositoryInterface $purchaseGuideArticleRepositoryInterface){
    }
    public function execute(int $id):array{
        return $this->purchaseGuideArticleRepositoryInterface->findById($id);
    }
}
