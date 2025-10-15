<?php

namespace App\Modules\DocumentType\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentDocumentType extends Model
{
    protected $table = 'document_types';

    protected $fillable = ['cod_sunat', 'description', 'abbreviation', 'st_sales', 'st_purchases', 'st_collections', 'status'];

    protected $hidden = ['created_at', 'updated_at'];
}
