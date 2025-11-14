<?php

namespace App\Modules\ShoppingIncomeGuide\Application\UseCases;

use App\Modules\ShoppingIncomeGuide\Application\DTOS\ShoppingIncomeGuideDTO;
use App\Modules\ShoppingIncomeGuide\Domain\Entities\ShoppingIncomeGuide;
use App\Modules\ShoppingIncomeGuide\Domain\Interface\ShoppingIncomeGuideRepositoryInterface;

class CreateShoppingIncomeGuideUseCase{
    public function __construct(
        private readonly ShoppingIncomeGuideRepositoryInterface $shoppingIncomeGuideRepository,)
        {}
        public function execute(ShoppingIncomeGuideDTO $shoppingIncomeGuideDTO):ShoppingIncomeGuide{
            $shoppingIncomeGuide = new ShoppingIncomeGuide(
                id:0,
                purchase_id:$shoppingIncomeGuideDTO->purchase_id,
                entry_guide_id:$shoppingIncomeGuideDTO->entry_guide_id,
            );
            return $this->shoppingIncomeGuideRepository->save($shoppingIncomeGuide);
        
        }
}