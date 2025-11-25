<?php

namespace App\Modules\Purchases\Domain\Interface;

use App\Modules\Purchases\Domain\Entities\Purchase;

interface PurchaseRepositoryInterface{
    public function findAll():array;
    public function findById(int $id):?Purchase;
    public function save(Purchase $purchase):?Purchase;
    public function update(Purchase $purchase):?Purchase;


}