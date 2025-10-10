<?php

namespace App\Modules\Branch\Application\UseCases;

use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;

class FindAllBranchUseCase{
    private branchRepositoryInterface $branchRepository;

    public function __construct(BranchRepositoryInterface $branchRepository){
       $this->branchRepository = $branchRepository;
    }
    public function execute(){
        return $this->branchRepository->findAllBranchs();
    }
}