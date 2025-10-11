<?php

namespace App\Modules\Departments\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentDepartment extends Model
{
    protected $table = 'departments';

    protected $fillable = ['coddep', 'nomdep'];

    protected $hidden = ['created_at', 'updated_at'];
}
