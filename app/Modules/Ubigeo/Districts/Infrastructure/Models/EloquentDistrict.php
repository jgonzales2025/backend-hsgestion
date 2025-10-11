<?php

namespace App\Modules\Ubigeo\Districts\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentDistrict extends Model
{
    protected $table = 'districts';
    protected $fillable = ['coddep', 'codpro', 'coddis', 'nompro'];
    protected $hidden = ['created_at', 'updated_at'];
}
