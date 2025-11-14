<?php

namespace App\Modules\DetailPurchaseGuides\Application\UseCases;

use App\Modules\DetailPurchaseGuides\Domain\Interface\DetailPurchaseGuideRepositoryInterface;

class FindAllDetailPurchaseGuide{
    public function __construct(private readonly DetailPurchaseGuideRepositoryInterface $detailPurchaseGuide){}

    public function execute():array{
        return $this->detailPurchaseGuide->findAll();
    }
}