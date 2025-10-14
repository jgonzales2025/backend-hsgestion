<?php

namespace App\Modules\EmissionReason\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentEmissionReason extends Model
{
    protected $table = 'emission_reasons';

    protected $fillable = ['description', 'status'];

    protected $hidden = ['created_at', 'updated_at'];
}
