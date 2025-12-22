<?php

namespace App\Modules\BuildDetailPc\Application\UseCases;

use App\Modules\BuildDetailPc\Application\DTOS\BuildDetailPcdto;
use App\Modules\BuildDetailPc\Domain\Entities\BuildDetailPc;
use App\Modules\BuildDetailPc\Domain\Interface\BuildDetailPcRepositoryInterface;

class UpdateBuildDetailPcUseCase
{
    public function __construct(
        private BuildDetailPcRepositoryInterface $buildDetailPcRepositoryInterface
    ) {}
    public function execute(BuildDetailPcdto $buildDetailPc, int $id)
    {
        $buildDetailPc = new BuildDetailPc(
            id: $id,
            build_pc_id: $buildDetailPc->build_pc_id,
            article_id: $buildDetailPc->article_id,
            quantity: $buildDetailPc->quantity,
            price: $buildDetailPc->price,
            subtotal: $buildDetailPc->subtotal
        );
        
        return $this->buildDetailPcRepositoryInterface->update($buildDetailPc);
    }
}