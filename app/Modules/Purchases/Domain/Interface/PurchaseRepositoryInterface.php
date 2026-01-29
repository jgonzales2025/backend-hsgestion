<?php

namespace App\Modules\Purchases\Domain\Interface;

use App\Modules\Purchases\Domain\Entities\Purchase;
use Illuminate\Support\Collection;

interface PurchaseRepositoryInterface
{
    public function findAll(?string $description, ?string $num_doc, ?int $id_proveedr, ?string $reference_correlative, ?string $reference_serie);
    public function findById(int $id): ?Purchase;
    public function save(Purchase $purchase): ?Purchase;
    public function update(Purchase $purchase): ?Purchase;
    public function getLastDocumentNumber(int $company_id, int $branch_id, string $serie): ?string;
    public function findBySerieAndCorrelative(string $serie, string $correlative): ?Purchase;
    public function findAllExcel(?string $description, ?int $num_doc, ?int $id_proveedr): Collection;
    public function dowloadPdf(int $id): ?Purchase;
    public function sp_registro_ventas_compras(int $company_id, string $date_start, string $date_end, int $tipo_doc, int $nrodoc_cli_pro, int $tipo_register);
    public function updateStatus(int $purchase, int $status): void;
    public function findAllDetalle(int $company_id, ?string $description, ?int $marca, ?int $cod_producto, ?int $sucursal, ?string $fecha_inicio, ?string $fecha_fin);
    public function findAllDetalleExcel(int $company_id, ?string $description, ?int $marca, ?int $cod_producto, ?int $sucursal, ?string $fecha_inicio, ?string $fecha_fin): Collection;
}
