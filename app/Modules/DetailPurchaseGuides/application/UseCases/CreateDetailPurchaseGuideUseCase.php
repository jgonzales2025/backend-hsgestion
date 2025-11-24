<?php

namespace App\Modules\DetailPurchaseGuides\Application\UseCases;

use App\Modules\DetailPurchaseGuides\Application\DTOS\DetailPurchaseGuideDTO;
use App\Modules\DetailPurchaseGuides\Domain\Entities\DetailPurchaseGuide;
use App\Modules\DetailPurchaseGuides\Domain\Interface\DetailPurchaseGuideRepositoryInterface;

class CreateDetailPurchaseGuideUseCase{
    public function __construct(private readonly DetailPurchaseGuideRepositoryInterface $detailPurchaseGuide){
    }

    public function execute(DetailPurchaseGuideDTO $detailPurchaseGuideDTO):?DetailPurchaseGuide{
        $detailPurchaseGuide =   new DetailPurchaseGuide(
                id: 0,
                article_id: $detailPurchaseGuideDTO->article_id,
                purchase_id: $detailPurchaseGuideDTO->purchase_id,
                description: $detailPurchaseGuideDTO->description,
                cantidad: $detailPurchaseGuideDTO->cantidad,
                precio_costo: $detailPurchaseGuideDTO->precio_costo,
                descuento: $detailPurchaseGuideDTO->descuento,
                sub_total: $detailPurchaseGuideDTO->sub_total,
                total: $detailPurchaseGuideDTO->total,
            );
        return $this->detailPurchaseGuide->save($detailPurchaseGuide);
    }
}