<?php

namespace App\Modules\Sale\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentSale extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'company_id',
        'document_type_id',
        'branch_id'
    ];
}
