<?php

namespace App\Modules\Company\Infrastructure\Model;

use App\Modules\Bank\Infrastructure\Models\EloquentBank;
use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\UserAssignment\Infrastructure\Models\EloquentUserAssignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EloquentCompany extends Model
{

     protected $table = 'companies';
    protected $fillable = ['ruc', 'company_name', 'address', 'ubigeo', 'start_date', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function branches(): HasMany
    {
        return $this->hasMany(EloquentBranch::class, 'cia_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(EloquentUserAssignment::class, 'company_id');
    }

    public function banks(): HasMany
    {
        return $this->hasMany(EloquentBank::class, 'company_id');
    }

    public function toDomain(EloquentCompany $eloquentCompany): Company
    {
        return new Company(
            id: $eloquentCompany->id,
            ruc: $eloquentCompany->ruc,
            company_name: $eloquentCompany->company_name,
            address: $eloquentCompany->address,
            start_date: $eloquentCompany->start_date,
            ubigeo: $eloquentCompany->ubigeo,
            status: $eloquentCompany->status
        );
    }
}
