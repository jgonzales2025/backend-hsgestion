<?php

namespace App\Modules\MeasurementUnit\Infrastructure\Models;

use App\Modules\MeasurementUnit\Domain\Entities\MeasurementUnit;
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

      public function toDomain(EloquentMeasurementUnit $eloquentMeasurementUnit): MeasurementUnit
    {
        return new MeasurementUnit(
            id: $eloquentMeasurementUnit->id,
            name:$eloquentMeasurementUnit->name,
            abbreviation:$eloquentMeasurementUnit->abbreviation,
            status:$eloquentMeasurementUnit->status
        );
    }
}
