<?php

namespace App\Modules\ShoppingIncomeGuide\Domain\Interface;

use App\Modules\ShoppingIncomeGuide\Domain\Entities\ShoppingIncomeGuide;

interface ShoppingIncomeGuideRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id):?ShoppingIncomeGuide;
    public function save(ShoppingIncomeGuide $shoppingIncomeGuide):?ShoppingIncomeGuide;

}