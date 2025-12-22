<?php

namespace App\Modules\BuildDetailPc\Application\UseCases;

use App\Modules\BuildDetailPc\Domain\Interface\BuildDetailPcRepositoryInterface;

class FindByIdBuildDetailPcUseCase
{
    public function __construct(
        private BuildDetailPcRepositoryInterface $buildDetailPcRepositoryInterface
    ) {}
    public function execute(int $id)
    {
            return $this->buildDetailPcRepositoryInterface->findById($id);
        
    }
}