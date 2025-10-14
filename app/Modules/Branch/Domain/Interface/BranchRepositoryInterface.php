<?php
namespace App\Modules\Branch\Domain\Interface;

use App\Modules\Branch\Domain\Entities\Branch;

interface BranchRepositoryInterface{
    public function findAllBranchs():array;

    public function findByCiaId(int $id):array;
     public function findById(int $id):?Branch;
    
    public function update(Branch $driver): void;

}