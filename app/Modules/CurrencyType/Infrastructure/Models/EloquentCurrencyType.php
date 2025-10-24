<?php

namespace App\Modules\CurrencyType\Infrastructure\Models;

use App\Modules\Bank\Infrastructure\Models\EloquentBank;
use App\Modules\CurrencyType\Domain\Entities\CurrencyType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EloquentCurrencyType extends Model{
    protected $table = "currency_types";

    protected $fillable = [
        'name',
        'commercial_symbol',
        'sunat_symbol',
        'status'
    ];
      protected $hidden = ['created_at', 'updated_at'];

      public function banks(): HasMany
      {
          return $this->hasMany(EloquentBank::class, 'currency_type_id');
      }

      public function toDomain(EloquentCurrencyType $eloquentCurrencyType): CurrencyType
      {
          return new CurrencyType(
              id: $eloquentCurrencyType->id,
              name: $eloquentCurrencyType->name,
              commercial_symbol: $eloquentCurrencyType->commercial_symbol,
              sunat_symbol: $eloquentCurrencyType->sunat_symbol,
              status: $eloquentCurrencyType->status
          );
      }
}
