<?php

namespace App\Modules\MonthlyClosure\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentMonthlyClosure extends Model
{
    protected $table = 'monthly_closures';

    protected $fillable = [
        'year',
        'month',
        'st_purchases',
        'st_sales',
        'st_cash',
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
