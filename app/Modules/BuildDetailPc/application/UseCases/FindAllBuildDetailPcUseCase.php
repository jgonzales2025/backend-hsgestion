<?php
namespace App\Modules\BuildDetailPc\Application\UseCases;

use App\Modules\BuildDetailPc\Domain\Interface\BuildDetailPcRepositoryInterface;

class FindAllBuildDetailPcUseCase
{
    public function __construct(
        private BuildDetailPcRepositoryInterface $buildDetailPcRepositoryInterface
    ) {}
    public function execute()
    {
        return $this->buildDetailPcRepositoryInterface->findAll();
    }
}