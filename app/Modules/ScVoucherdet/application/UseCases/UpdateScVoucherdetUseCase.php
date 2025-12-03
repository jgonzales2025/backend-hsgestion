<?php

namespace App\Modules\ScVoucherdet\application\UseCases;

use App\Modules\ScVoucherdet\application\DTOS\ScVoucherdetDTO;
use App\Modules\ScVoucherdet\Domain\Entities\ScVoucherdet;
use App\Modules\ScVoucherdet\Domain\Interface\ScVoucherdetRepositoryInterface;

class UpdateScVoucherdetUseCase
{
    public function __construct(
        private ScVoucherdetRepositoryInterface $scVoucherdetRepository,
    ) {}
    public function execute(ScVoucherdetDTO $scVoucherdetDTO, int $id)
    {
        $scVoucherdet = new ScVoucherdet(
            id: $id,
            cia: $scVoucherdetDTO->cia,
            codcon: $scVoucherdetDTO->codcon,
            tipdoc: $scVoucherdetDTO->tipdoc,
            numdoc: $scVoucherdetDTO->numdoc,
            glosa: $scVoucherdetDTO->glosa,
            impsol: $scVoucherdetDTO->impsol,
            impdol: $scVoucherdetDTO->impdol,
        );

        return $this->scVoucherdetRepository->update($scVoucherdet);
    }
}