<?php

namespace App\Modules\ShoppingIncomeGuide\Infrastructure\Models;

use App\Modules\EntryGuides\Infrastructure\Models\EloquentEntryGuide;
use App\Modules\ShoppingIncomeGuide\Domain\Entities\ShoppingIncomeGuide;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentShoppingIncomeGuide extends Model
{
    protected $table = 'shopping_income_guide';


    protected $fillable = [
        'purchase_id',
        'entry_guide_id',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function entryGuide(): BelongsTo
    {
        return $this->belongsTo(EloquentEntryGuide::class, 'entry_guide_id');
    }

    public function toDomain(): ?ShoppingIncomeGuide
    {
        return new ShoppingIncomeGuide(
            id: $this->id,
            purchase_id: $this->purchase_id,
            entry_guide_id: $this->entry_guide_id,
        );
    }
}
