<?php

namespace App\Modules\DetailPcCompatible\application\UseCases;

use App\Modules\DetailPcCompatible\application\DTOS\DetailPcCompatibleDTO;
use App\Modules\DetailPcCompatible\Domain\Entities\DetailPcCompatible;
use App\Modules\DetailPcCompatible\Domain\Interface\DetailPcCompatibleRepositoryInterface;

class CreateDetailPcCompatibleUseCase
{
    public function __construct(
        private DetailPcCompatibleRepositoryInterface $detailPcCompatibleRepositoryInterface
    ) {}
    public function execute(DetailPcCompatibleDTO $data): ?DetailPcCompatible
    {
        $detailPcCompatible = new DetailPcCompatible(
            id: 0,
            article_major_id: $data->article_major_id,
            article_accesory_id: $data->article_accesory_id,
            status: $data->status
        );

        return $this->detailPcCompatibleRepositoryInterface->create($detailPcCompatible);
    }
}
