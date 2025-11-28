<?php

namespace App\Modules\PettyCashReceipt\application\UseCases;

use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashReceiptRepositoryInterface;

class SelectProcedureUseCase
{
    public function __construct(
        private readonly PettyCashReceiptRepositoryInterface $pettyCashReceiptRepository
    ) {}
    public function execute(
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
    ) {
      return  $this->pettyCashReceiptRepository->selectProcedure($cia, $fecha, $fechaU, $nrocliente, $pcodsuc, $ptippag, $pcodban, $pnroope, $ptipdoc, $pserie, $pcorrelativo);
    }
}