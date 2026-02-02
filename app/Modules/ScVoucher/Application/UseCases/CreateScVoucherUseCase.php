<?php

namespace App\Modules\ScVoucher\Application\UseCases;

use App\Modules\ScVoucher\Application\DTOS\ScVoucherDTO;
use App\Modules\ScVoucher\Domain\Entities\ScVoucher;
use App\Modules\ScVoucher\Domain\Interface\ScVoucherRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;

class CreateScVoucherUseCase
{
    public function __construct(
        private ScVoucherRepositoryInterface $scVoucherRepository,
        private DocumentNumberGeneratorService $documentNumberGeneratorService,
        private \App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface $customerRepository,
        private \App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private \App\Modules\PaymentMethodsSunat\Domain\Interface\PaymentMethodSunatRepositoryInterface $paymentMethodSunatRepository,
        private \App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface $paymentMethodRepository,
        private \App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface $bankRepository,
    ) {}

    public function execute(ScVoucherDTO $scVoucherDTO): ?ScVoucher
    {
        $lastDocumentNumber = $this->scVoucherRepository->getLastDocumentNumber($scVoucherDTO->nroope);
        if (empty($scVoucherDTO->correlativo)) {
            $scVoucherDTO->correlativo = $this->documentNumberGeneratorService->generateNextNumber($lastDocumentNumber);
        }

        $customer = $this->customerRepository->findById($scVoucherDTO->codigo);
        $currencyType = $this->currencyTypeRepository->findById($scVoucherDTO->tipmon);
        $paymentMethodSunat = $this->paymentMethodSunatRepository->findById($scVoucherDTO->medpag);
        $paymentMethod = $this->paymentMethodRepository->findById($scVoucherDTO->tipopago);
        $bank = $this->bankRepository->findById($scVoucherDTO->codban);

        $scVoucher = new ScVoucher(
            id: null,
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
            path_image: $scVoucherDTO->path_image,
            details: $scVoucherDTO->detail_sc_voucher,
            detailVoucherpurchase: $scVoucherDTO->detail_voucher_purchase,
        );

        return $this->scVoucherRepository->create($scVoucher);
    }
}
