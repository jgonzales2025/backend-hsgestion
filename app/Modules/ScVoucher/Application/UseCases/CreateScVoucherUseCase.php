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
        private \App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface $paymentTypeRepository,
        private \App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface $bankRepository,
    ) {}

    public function execute(ScVoucherDTO $scVoucherDTO): ?ScVoucher
    {
        $lastDocumentNumber = $this->scVoucherRepository->getLastDocumentNumber($scVoucherDTO->nroope);
        $scVoucherDTO->correlativo = $this->documentNumberGeneratorService->generateNextNumber($lastDocumentNumber);

        $customer = $this->customerRepository->findById($scVoucherDTO->codigo);
        $currencyType = $this->currencyTypeRepository->findById($scVoucherDTO->tipmon);
        $paymentMethodSunat = $this->paymentMethodSunatRepository->findById($scVoucherDTO->medpag);
        $paymentType = $this->paymentTypeRepository->findById($scVoucherDTO->tipopago);
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
            tipopago: $paymentType,
            status: $scVoucherDTO->status,
            usradi: $scVoucherDTO->usradi,
            fecadi: $scVoucherDTO->fecadi,
            usrmod: $scVoucherDTO->usrmod,
        );

        return $this->scVoucherRepository->create($scVoucher);
    }
}
