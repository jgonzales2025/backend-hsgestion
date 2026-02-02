<?php

namespace App\Modules\Serie\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentSerie extends Model
{
    protected $table = 'series';

    protected $fillable = ['company_id', 'serie_number', 'branch_id', 'elec_document_type_id', 'dir_document_type_id', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

}
