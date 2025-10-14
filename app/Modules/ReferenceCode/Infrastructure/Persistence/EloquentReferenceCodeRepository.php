<?php
namespace App\Modules\ReferenceCode\Infrastructure\Persistence;

use App\Modules\ReferenceCode\Domain\Entities\ReferenceCode;
use App\Modules\ReferenceCode\Domain\Interfaces\ReferenceCodeRepositoryInterface;
use App\Modules\ReferenceCode\Infrastructure\Models\EloquentReferenceCode;

class EloquentReferenceCodeRepository implements ReferenceCodeRepositoryInterface{
    public function findAllReferenceCode():array{
           $referenceCode = EloquentReferenceCode::all();


    }
    public function findById(int $id):ReferenceCode{
        
    }
}