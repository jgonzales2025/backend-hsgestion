<?php

namespace App\Modules\PercentageIGV\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentPercentageIGV extends Model
{
    protected $table = 'percentage_igvs';

    protected $fillable = ['date', 'percentage'];

    protected $hidden = ['created_at', 'updated_at'];
}
