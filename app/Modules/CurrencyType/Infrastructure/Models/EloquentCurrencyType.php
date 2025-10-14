<?php

namespace App\Modules\CurrencyType\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentCurrencyType extends Model{
    protected $table = "currency_types";
   
    protected $fillable = [
        'name',
        'status'
    ];
      protected $hidden = ['created_at', 'updated_at'];

}