<?php

namespace App\Modules\ScVoucher\Application\UseCases;

use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\PaymentMethodsSunat\Domain\Interface\PaymentMethodSunatRepositoryInterface;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;
use App\Modules\ScVoucher\Application\DTOS\ScVoucherDTO;
use App\Modules\ScVoucher\Domain\Entities\ScVoucher;
use App\Modules\ScVoucher\Domain\Interface\ScVoucherRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;

class UpdateScVoucherUseCase
{
    public function __construct(
        private ScVoucherRepositoryInterface $scVoucherRepository,
        private CustomerRepositoryInterface $customerRepository,
        private CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private PaymentMethodSunatRepositoryInterface $paymentMethodSunatRepository,
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
        private BankRepositoryInterface $bankRepository,
    ) {}

    public function execute(ScVoucherDTO $scVoucherDTO, int $id): ?ScVoucher
    {

        $customer = $this->customerRepository->findById($scVoucherDTO->codigo);
        $currencyType = $this->currencyTypeRepository->findById($scVoucherDTO->tipmon);
        $paymentMethodSunat = $this->paymentMethodSunatRepository->findById($scVoucherDTO->medpag);
        $paymentMethod = $this->paymentMethodRepository->findById($scVoucherDTO->tipopago);
        $bank = $this->bankRepository->findById($scVoucherDTO->codban);

        $scVoucher = new ScVoucher(
            id: $id,
            cia: $scVoucherDTO->cia,
            anopr: $scVoucherDTO->anopr,
            correlativo: $scVoucherDTO->correlativo,
            fecha: $scVoucherDTO->fecha,
            codban: $bank,
            codigo: $customer,
            nroope: $scVoucherDTO->nroope,
            glosa: $scVoucherDTO->glosa,
            orden: $scVoucherDTO->orden,
            tipmon: $currencyType,
            tipcam: $scVoucherDTO->tipcam,
            total: $scVoucherDTO->total,
            medpag: $paymentMethodSunat,
            tipopago: $paymentMethod,
            status: $scVoucherDTO->status,
            usradi: $scVoucherDTO->usradi,
            fecadi: $scVoucherDTO->fecadi,
            usrmod: $scVoucherDTO->usrmod,
            details: $scVoucherDTO->detail_sc_voucher,
            detailVoucherpurchase: $scVoucherDTO->detail_voucher_purchase,
        );

        return $this->scVoucherRepository->update($scVoucher);
    }
}
