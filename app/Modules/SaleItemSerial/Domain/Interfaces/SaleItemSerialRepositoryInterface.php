<?php

namespace App\Modules\SaleItemSerial\Domain\Interfaces;

use App\Modules\SaleItemSerial\Domain\Entities\SaleItemSerial;

interface SaleItemSerialRepositoryInterface
{
    public function save(SaleItemSerial $saleItemSerial): SaleItemSerial;
    public function findSerialsBySaleId(int $saleId): array;
    public function deleteSerialsBySaleId(int $saleId): void;
}
