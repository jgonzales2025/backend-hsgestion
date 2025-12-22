<?php

namespace App\Modules\ShoppingIncomeGuide\Application\DTOS;

class ShoppingIncomeGuideDTO
{
    public int $purchase_id;
    public  $entry_guide_id;

    public function __construct(array $array) {
        $this->purchase_id = $array['purchase_id'] ?? 1;
        $this->entry_guide_id = $array['entry_guide_id'] ;
    }
}