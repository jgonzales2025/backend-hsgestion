<?php

namespace App\Modules\Ubigeo\Provinces\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentProvince extends Model
{
    protected $table = 'provinces';
    protected $fillable = ['coddep', 'codpro', 'nompro'];
    protected $hidden = ['created_at', 'updated_at'];
}
