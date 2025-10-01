<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordType extends Model
{
    protected $fillable = ['name', 'abbreviation', 'status'];

    protected $hidden = ['created_at', 'updated_at'];
}
