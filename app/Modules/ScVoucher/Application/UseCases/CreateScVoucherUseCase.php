<?php

namespace App\Modules\ScVoucher\Application\UseCases;

use App\Modules\ScVoucher\Application\DTOS\ScVoucherDTO;
use App\Modules\ScVoucher\Domain\Entities\ScVoucher;
use App\Modules\ScVoucher\Domain\Interface\ScVoucherRepositoryInterface;

class CreateScVoucherUseCase
{
    public function __construct(
        private ScVoucherRepositoryInterface $scVoucherRepository,
    ) {}

    public function execute(ScVoucherDTO $scVoucherDTO): ?ScVoucher
    {
        $scVoucher = new ScVoucher(
            id: null,
            cia: $scVoucherDTO->cia,
            anopr: $scVoucherDTO->anopr,
            correlativo: $scVoucherDTO->correlativo,
            fecha: $scVoucherDTO->fecha,
            codban: $scVoucherDTO->codban,
            codigo: $scVoucherDTO->codigo,
            nroope: $scVoucherDTO->nroope,
            glosa: $scVoucherDTO->glosa,
            orden: $scVoucherDTO->orden,
            tipmon: $scVoucherDTO->tipmon,
            tipcam: $scVoucherDTO->tipcam,
            total: $scVoucherDTO->total,
            medpag: $scVoucherDTO->medpag,
            tipopago: $scVoucherDTO->tipopago,
            status: $scVoucherDTO->status,
            usradi: $scVoucherDTO->usradi,
            fecadi: $scVoucherDTO->fecadi,
            usrmod: $scVoucherDTO->usrmod,
        );

        return $this->scVoucherRepository->create($scVoucher);
    }
}
