<?php

namespace App\Modules\SaleItemSerial\Application\UseCases;

use App\Modules\SaleItemSerial\Application\DTOs\SaleItemSerialDTO;
use App\Modules\SaleItemSerial\Domain\Entities\SaleItemSerial;
use App\Modules\SaleItemSerial\Domain\Interfaces\SaleItemSerialRepositoryInterface;

readonly class CreateSaleItemSerialUseCase
{
    public function __construct(private readonly SaleItemSerialRepositoryInterface $saleItemSerialRepository){}

    public function execute(SaleItemSerialDTO $saleItemSerialDTO): SaleItemSerial
    {

        $saleItemSerial = new SaleItemSerial(
            id: 0,
            sale: $saleItemSerialDTO->sale,
            article: $saleItemSerialDTO->article,
            serial: $saleItemSerialDTO->serial
        );

        return $this->saleItemSerialRepository->save($saleItemSerial);
    }
}
