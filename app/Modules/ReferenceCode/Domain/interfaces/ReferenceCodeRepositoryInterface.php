<?php

namespace App\Modules\ReferenceCode\Domain\Interfaces;

use App\Modules\ReferenceCode\Domain\Entities\ReferenceCode;

interface ReferenceCodeRepositoryInterface{
    public function findAllReferenceCode():array;
    public function findById(int $id): ?ReferenceCode;
     public function update(ReferenceCode $referenceCode) :void;
}