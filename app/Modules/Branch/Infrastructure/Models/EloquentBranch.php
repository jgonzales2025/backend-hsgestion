<?php

namespace App\Modules\Branch\Infrastructure\Models;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\BranchPhone\Infrastructure\Model\EloquentBranchPhone;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EloquentBranch extends Model
{
     protected $table = 'branches';
    protected $fillable = ['cia_id', 'name', 'address', 'email', 'start_date', 'serie', 'status', 'st_sales', 'st_dispatch_notes', 'st_petty_cash', 'st_warranties'];

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

    public function toDomain(EloquentBranch $eloquentBranch): ?Branch
    {
        return new Branch(
            id: $eloquentBranch->id,
            cia_id: $eloquentBranch->company->id,
            name: $eloquentBranch->name,
            address: $eloquentBranch->address,
            email: $eloquentBranch->email,
            start_date: $eloquentBranch->start_date,
            serie: $eloquentBranch->serie,
            status: $eloquentBranch->status,
            st_sales: $eloquentBranch->st_sales,
            st_dispatch_notes: $eloquentBranch->st_dispatch_notes,
            st_petty_cash: $eloquentBranch->st_petty_cash,
            st_warranties: $eloquentBranch->st_warranties
        );
    }
}

