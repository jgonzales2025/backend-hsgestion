<?php
namespace App\Modules\DetailPcCompatible\application\UseCases;

use App\Modules\BuildDetailPc\Domain\Interface\BuildDetailPcRepositoryInterface;
use App\Modules\DetailPcCompatible\Domain\Interface\DetailPcCompatibleRepositoryInterface;

class FindByIdDetailPcCompatibleUseCase
{
    public function __construct(
        private DetailPcCompatibleRepositoryInterface $buildDetailPcRepositoryInterface
    ) {}
    public function execute(int $id)
    {
        return $this->buildDetailPcRepositoryInterface->findById($id);
        
    }
}
