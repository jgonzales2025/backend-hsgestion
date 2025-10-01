<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable = ['cod_sunat', 'description', 'abbreviation', 'st_sales', 'st_purchases', 'st_collections', 'status'];

    protected $hidden = ['created_at', 'updated_at'];
}
