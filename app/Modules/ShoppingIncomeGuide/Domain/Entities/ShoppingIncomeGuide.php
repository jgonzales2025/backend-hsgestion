<?php

namespace App\Modules\ShoppingIncomeGuide\Domain\Entities;

class ShoppingIncomeGuide
{
    private ?int $id;
    private int $purchase_id;
    private ?int $entry_guide_id;

    public function __construct(
        ?int $id,
        int $purchase_id,
        ?int $entry_guide_id,
    ) {
        $this->id = $id;
        $this->purchase_id = $purchase_id;
        $this->entry_guide_id = $entry_guide_id;
    }
    public function getId(): int|null
    {
        return $this->id;
    }
    public function getPurchaseId(): int
    {
        return $this->purchase_id;
    }
    public function getEntryGuideId(): int|null
    {
        return $this->entry_guide_id;
    }

}