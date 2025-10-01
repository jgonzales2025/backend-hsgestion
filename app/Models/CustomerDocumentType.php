<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDocumentType extends Model
{
    protected $fillable = ['cod_sunat', 'description', 'abbreviation', 'st_driver', 'status'];

    protected $hidden = ['created_at', 'updated_at'];
}
