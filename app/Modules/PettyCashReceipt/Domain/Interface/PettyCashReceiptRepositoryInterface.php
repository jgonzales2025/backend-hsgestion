<?php

namespace App\Modules\PettyCashReceipt\Domain\Interface;

use App\Modules\PettyCashReceipt\Domain\Entities\PettyCashReceipt;

interface PettyCashReceiptRepositoryInterface
{
  public function findAll(?string $filter, ?int $currency_type, ?int $is_active);
  public function findById(int $id): ?PettyCashReceipt;
  public function save(PettyCashReceipt $pettyCashReceipt): ?PettyCashReceipt;
  public function update(PettyCashReceipt $pettyCashReceipt): ?PettyCashReceipt;
  public function getLastDocumentNumber(string $serie): ?string;
  public function updateStatus(int $pettyCashReceipt, int $status): void;
  public function selectProcedure(
    $cia,
    $fecha,
    $fechaU,
    $nrocliente,
    $pcodsuc,
    $ptippag,
    $pcodban,
    $pnroope,
    $ptipdoc,
    $pserie,
    $pcorrelativo
  ): array;
  public function cobranzaDetalle($cia,
        $fecha,
        $fechaU,
        $nrocliente,
        $pcodsuc,
        $ptippag,
        $pcodban,
        $pnroope,
        $ptipdoc,
        $pserie,
        $pcorrelativo):array;
}
