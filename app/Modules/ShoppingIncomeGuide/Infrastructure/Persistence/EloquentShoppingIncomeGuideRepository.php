<?php

namespace App\Modules\ShoppingIncomeGuide\Infrastructure\Persistence;

use App\Modules\ShoppingIncomeGuide\Domain\Entities\ShoppingIncomeGuide;
use App\Modules\ShoppingIncomeGuide\Domain\Interface\ShoppingIncomeGuideRepositoryInterface;
use App\Modules\ShoppingIncomeGuide\Infrastructure\Models\EloquentShoppingIncomeGuide;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;

class EloquentShoppingIncomeGuideRepository implements ShoppingIncomeGuideRepositoryInterface
{
  public function findAll(): array
  {
    $eloquentShoppingIncomeGuide = EloquentShoppingIncomeGuide::all();
    return $eloquentShoppingIncomeGuide->map(function ($item) {
      return new ShoppingIncomeGuide(
        id: $item->id,
        purchase_id: $item->purchase_id,
        entry_guide_id: $item->entry_guide_id
      );
    })->toArray();
  }
  public function findById(int $id): array
  {
    $eloquentShoppingIncomeGuide = EloquentShoppingIncomeGuide::where('purchase_id', $id)->get();
    return $eloquentShoppingIncomeGuide->map(function ($item) {
      return new ShoppingIncomeGuide(
        id: $item->id,
        purchase_id: $item->purchase_id,
        entry_guide_id: $item->entry_guide_id
      );
    })->toArray();
  }
  public function findByEntryGuideId(int $id): array
  {
    $eloquentShoppingIncomeGuide = EloquentShoppingIncomeGuide::where('entry_guide_id', $id)->get();
    return $eloquentShoppingIncomeGuide->map(function ($item) {
      return new ShoppingIncomeGuide(
        id: $item->id,
        purchase_id: $item->purchase_id,
        entry_guide_id: $item->entry_guide_id
      );
    })->toArray();
  }
  public function save(ShoppingIncomeGuide $shoppingIncomeGuide): ?ShoppingIncomeGuide
  {
    $eloquentShoppingIncomeGuide = EloquentShoppingIncomeGuide::create([
      'purchase_id' => $shoppingIncomeGuide->getPurchaseId(),
      'entry_guide_id' => $shoppingIncomeGuide->getEntryGuideId(),
    ]);

    return new ShoppingIncomeGuide(
      id: $eloquentShoppingIncomeGuide->id,
      purchase_id: $eloquentShoppingIncomeGuide->purchase_id,
      entry_guide_id: $eloquentShoppingIncomeGuide->entry_guide_id
    );
  }
  public function deletedBy(int $id): void
  {
    EloquentShoppingIncomeGuide::where('purchase_id', $id)->delete();
  }
}
