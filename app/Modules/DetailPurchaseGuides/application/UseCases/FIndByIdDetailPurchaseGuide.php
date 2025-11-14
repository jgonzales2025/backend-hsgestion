<?php

namespace App\Modules\DetailPurchaseGuides\Application\UseCases;

use App\Modules\DetailPurchaseGuides\Domain\Entities\DetailPurchaseGuide;
use App\Modules\DetailPurchaseGuides\Domain\Interface\DetailPurchaseGuideRepositoryInterface;

class FIndByIdDetailPurchaseGuide{
      public function __construct(private readonly DetailPurchaseGuideRepositoryInterface $detailPurchaseGuide){}

      public function execute(int $id):?DetailPurchaseGuide{
        return $this->detailPurchaseGuide->findById($id);
    }
}