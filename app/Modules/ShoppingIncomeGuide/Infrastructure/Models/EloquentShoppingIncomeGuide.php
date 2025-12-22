<?php

namespace App\Modules\ShoppingIncomeGuide\Infrastructure\Models;

use App\Modules\ShoppingIncomeGuide\Domain\Entities\ShoppingIncomeGuide;
use Illuminate\Database\Eloquent\Model;

class EloquentShoppingIncomeGuide extends Model{
    protected $table = 'shopping_income_guide';


    protected $fillable = [
        'purchase_id',
        'entry_guide_id',
    ];

    protected $hidden = ['created_at', 'updated_at'];
   
    public function toDomain(): ?ShoppingIncomeGuide
    {
        return new ShoppingIncomeGuide(
            id: $this->id,
            purchase_id: $this->purchase_id,
            entry_guide_id: $this->entry_guide_id,
        );
    }
}