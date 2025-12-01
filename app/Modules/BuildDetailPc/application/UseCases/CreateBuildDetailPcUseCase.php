<?php

namespace App\Modules\BuildDetailPc\application\UseCases;

use App\Modules\BuildDetailPc\application\DTOS\BuildDetailPcdto;
use App\Modules\BuildDetailPc\Domain\Entities\BuildDetailPc;
use App\Modules\BuildDetailPc\Domain\Interface\BuildDetailPcRepositoryInterface;

class CreateBuildDetailPcUseCase
{
    public function __construct(
        private BuildDetailPcRepositoryInterface $buildDetailPcRepositoryInterface
    ) {}
    public function execute(BuildDetailPcdto $buildDetailPc)
    {
        $buildDetailPc = new BuildDetailPc(
            id: 0,
            build_pc_id: $buildDetailPc->build_pc_id,
            article_id: $buildDetailPc->article_id,
            quantity: $buildDetailPc->quantity,
            price: $buildDetailPc->price,
            subtotal: $buildDetailPc->subtotal
        );
        return $this->buildDetailPcRepositoryInterface->create($buildDetailPc);
    }
}