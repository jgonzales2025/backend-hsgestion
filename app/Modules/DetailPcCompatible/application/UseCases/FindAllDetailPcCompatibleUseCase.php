<?php

namespace App\Modules\DetailPcCompatible\application\UseCases;


use App\Modules\DetailPcCompatible\Domain\Interface\DetailPcCompatibleRepositoryInterface;

class FindAllDetailPcCompatibleUseCase
{
    public function __construct(
        private DetailPcCompatibleRepositoryInterface $detailPcCompatibleRepositoryInterface
    ) {}
    public function execute()
    {
        return $this->detailPcCompatibleRepositoryInterface->findAll();
        
    }
}
