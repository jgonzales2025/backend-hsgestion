<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = ['ruc', 'company_name', 'address', 'ubigeo', 'start_date', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class, 'cia_id');
    }
}
