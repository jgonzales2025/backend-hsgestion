<?php

namespace App\Modules\Detraction\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentDetraction extends Model
{
    protected $table = 'detractions';

    protected $fillable = [
        'cod_sunat',
        'description',
        'percentage',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}