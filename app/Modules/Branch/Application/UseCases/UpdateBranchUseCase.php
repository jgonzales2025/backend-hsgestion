<?php
namespace App\Modules\Branch\Application\UseCases;

use App\Modules\Branch\Application\DTOs\BranchDTO;
use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;


class UpdateBranchUseCase{
    private branchRepositoryInterface $branchRepository;

    public function __construct(BranchRepositoryInterface $branchRepository){
        $this->branchRepository = $branchRepository;
    }
    public function execute(int $id, BranchDTO $branchDTO){
        $existingBranch= $this->branchRepository->findById($id);
        if (!$existingBranch) {
            return null;
        }
        $branch = new Branch(
            id:$id,
            cia_id:$branchDTO->cia_id,
            name:$branchDTO->name,
            address:$branchDTO->address,
            email:$branchDTO->email,
            start_date:$branchDTO->start_date,
            serie:$branchDTO->serie,
            status:$branchDTO->status,
            phones: $branchDTO->phones
        );
        $this->branchRepository->update($branch);
    }
}