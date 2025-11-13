<?php

namespace App\Modules\PettyCashMotive\Domain\Interface;

use App\Modules\PettyCashMotive\Domain\Entities\PettyCashMotive;

interface PettyCashMotiveInterfaceRepository{
    public function save(PettyCashMotive $pettyCashMotive): ?PettyCashMotive;
    public function update(PettyCashMotive $pettyCashMotive): ?PettyCashMotive;
    public function findAll(?string $receipt_type):array;
    public function findById(int $id): ?PettyCashMotive;
    
}