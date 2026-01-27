<?php

namespace App\Modules\ScVoucherdet\application\UseCases;

use App\Modules\PaymentConcept\Application\UseCases\FindByIdPaymentConceptUseCase;
use App\Modules\PaymentConcept\Domain\Interfaces\PaymentConceptRepositoryInterface;
use App\Modules\ScVoucherdet\application\DTOS\ScVoucherdetDTO;
use App\Modules\ScVoucherdet\Domain\Entities\ScVoucherdet;
use App\Modules\ScVoucherdet\Domain\Interface\ScVoucherdetRepositoryInterface;

class CreateScVoucherdetUseCase
{
    public function __construct(
        private ScVoucherdetRepositoryInterface $scVoucherdetRepository,
        private PaymentConceptRepositoryInterface $paymentConceptRepository,
    ) {}
    public function execute(ScVoucherdetDTO $scVoucherdetDTO)
    {
        $findByIdUseCase = new FindByIdPaymentConceptUseCase($this->paymentConceptRepository);
        $paymentConcept = $findByIdUseCase->execute($scVoucherdetDTO->codcon);

        $scVoucherdet = new ScVoucherdet(
            id: 0,
            cia: $scVoucherdetDTO->cia,
            codcon: $paymentConcept,
            tipdoc: $scVoucherdetDTO->tipdoc,
            glosa: $scVoucherdetDTO->glosa,
            impsol: $scVoucherdetDTO->impsol,
            impdol: $scVoucherdetDTO->impdol,
            id_purchase: $scVoucherdetDTO->id_purchase,
            id_sc_voucher: $scVoucherdetDTO->id_sc_voucher,
            numdoc: $scVoucherdetDTO->numdoc,
            correlativo: $scVoucherdetDTO->correlativo,
            serie: $scVoucherdetDTO->serie,
        );

        return $this->scVoucherdetRepository->create($scVoucherdet);
    }
}
