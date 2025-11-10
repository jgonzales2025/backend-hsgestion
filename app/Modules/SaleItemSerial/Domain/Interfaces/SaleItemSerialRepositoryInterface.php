<?php

namespace App\Modules\SaleItemSerial\Domain\Interfaces;

interface SaleItemSerialRepositoryInterface
{
    public function save(array $saleItemSerials): void;
}
