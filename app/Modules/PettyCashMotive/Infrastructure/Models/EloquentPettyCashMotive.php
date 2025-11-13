<?php

namespace App\Modules\PettyCashMotive\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentPettyCashMotive extends Model{

   protected $table = 'petty_cash_motive';
   protected $fillable = [
           'company_id',
           'description',
           'receipt_type',
           'user_id',
           'date',
           'user_mod',
           'date_mod',
           'status',
   ];
         protected $hidden = ['created_at', 'updated_at'];
   
}