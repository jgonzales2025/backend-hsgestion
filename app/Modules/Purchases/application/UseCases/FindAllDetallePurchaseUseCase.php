<?php

namespace App\Modules\Purchases\Application\UseCases;

use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;

class FindAllDetallePurchaseUseCase
{
    public function __construct(private readonly PurchaseRepositoryInterface $purchaseRepository) {}

    public function execute(int $company_id, ?string $description, ?int $marca, ?int $cod_producto, ?int $sucursal, ?string $fecha_inicio, ?string $fecha_fin)
    {
        return $this->purchaseRepository->findAllDetalle($company_id, $description, $marca, $cod_producto, $sucursal, $fecha_inicio, $fecha_fin);
    }

    public function executeExcel(int $company_id, ?string $description, ?int $marca, ?int $cod_producto, ?int $sucursal, ?string $fecha_inicio, ?string $fecha_fin)
    {
        return $this->purchaseRepository->findAllDetalleExcel($company_id, $description, $marca, $cod_producto, $sucursal, $fecha_inicio, $fecha_fin);
    }
}
