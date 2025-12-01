<?php
namespace App\Modules\BuildPc\application\UseCases;

use App\Modules\BuildPc\Domain\Interface\BuildPcRepositoryInterface;

class FindByIdBuildPcUseCase
{
    public function __construct(
        private BuildPcRepositoryInterface $buildPcRepository
    ) {}
    public function execute(int $id)
    {
        return $this->buildPcRepository->findById($id);
    }
}