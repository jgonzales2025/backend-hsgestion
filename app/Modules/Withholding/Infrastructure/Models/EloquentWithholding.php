<?php

namespace App\Modules\Withholding\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentWithholding extends Model
{
    protected $table = 'withholdings';

    protected $fillable = [
        'date',
        'percentage',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}