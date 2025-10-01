<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyType extends Model
{
    protected $fillable = ['name', 'status'];

    protected $hidden = ['created_at', 'updated_at'];
}
