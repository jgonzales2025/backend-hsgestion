<?php
namespace App\Modules\BuildPc\application\UseCases;

use App\Modules\BuildPc\Domain\Interface\BuildPcRepositoryInterface;

class FindAllBuildPcUseCase
{
    public function __construct(
        private BuildPcRepositoryInterface $buildPcRepository
    ) {}
    public function execute()
    {
        return $this->buildPcRepository->findAll();
    }
}