<?php

namespace App\Modules\Brand\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentBrand extends Model
{
    protected $table = 'brands';
    protected $fillable = ['name', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

}
