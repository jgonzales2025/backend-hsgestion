<?php

namespace App\Modules\DetailPurchaseGuides\Domain\Interface;

use App\Modules\DetailPurchaseGuides\Domain\Entities\DetailPurchaseGuide;

interface DetailPurchaseGuideRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): array;
    public function findByDetailId(int $id): ?DetailPurchaseGuide;
    public function save(DetailPurchaseGuide $detailPurchaseGuide): ?DetailPurchaseGuide;
    public function deletedBy(int $id): void;
}
