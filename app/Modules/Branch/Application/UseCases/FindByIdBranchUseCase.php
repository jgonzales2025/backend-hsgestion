<?php
namespace App\Modules\Branch\Application\UseCases;

use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;

class FindByIdBranchUseCase{
    private branchRepositoryInterface $branchRepository;
   
    public function __construct(BranchRepositoryInterface $branchRepository){
        $this->branchRepository = $branchRepository;
    }
    public function execute($id){
        return $this->branchRepository->findById($id);
    }
}