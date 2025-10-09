<?php

namespace App\Models;

use App\Modules\Driver\Infrastructure\Models\EloquentDriver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerDocumentType extends Model
{
    protected $fillable = ['cod_sunat', 'description', 'abbreviation', 'st_driver', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function drivers(): HasMany
    {
        return $this->hasMany(EloquentDriver::class, 'customer_document_type_id');
    }
}
