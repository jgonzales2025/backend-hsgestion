<?php

namespace App\Modules\ReferenceCode\Domain\Interfaces;

use App\Modules\ReferenceCode\Domain\Entities\ReferenceCode;

interface ReferenceCodeRepositoryInterface{
    public function save(int $id,ReferenceCode $referenceCode):?ReferenceCode;
    public function findAllReferenceCode():array;
    public function findById(int $id): array;
     public function update(ReferenceCode $referenceCode) :void;
     public function indexid(int $id) :?ReferenceCode;
}