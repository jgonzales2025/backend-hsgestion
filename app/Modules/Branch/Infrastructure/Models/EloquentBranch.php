<?php

namespace App\Modules\Branch\Infrastructure\Models;

use App\Modules\BranchPhone\Infrastructure\Model\EloquentBranchPhone;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EloquentBranch extends Model
{
     protected $table = 'branches';
    protected $fillable = ['cia_id', 'name', 'address', 'email', 'start_date', 'serie', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'cia_id');

    }

      // 🔹 Relación con los teléfonos (opcional)
    public function phones(): HasMany
    {
        return $this->hasMany(EloquentBranchPhone::class, 'branch_id');
    }
}

