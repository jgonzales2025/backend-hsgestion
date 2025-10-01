<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['ruc', 'company_name', 'address', 'ubigeo', 'start_date', 'status'];

    protected $hidden = ['created_at', 'updated_at'];
}
