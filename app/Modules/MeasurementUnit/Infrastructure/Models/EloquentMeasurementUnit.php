<?php

namespace App\Modules\MeasurementUnit\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentMeasurementUnit extends Model
{
    protected $table = 'measurement_units';

    protected $fillable = [
        'name',
        'abbreviation',
        'status',
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
