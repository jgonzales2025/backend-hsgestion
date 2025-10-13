<?php

namespace App\Modules\Ubigeo\Departments\Infrastructure\Models;

use App\Modules\Ubigeo\Departments\Domain\Entities\Department;
use Illuminate\Database\Eloquent\Model;

class EloquentDepartment extends Model
{
    protected $table = 'departments';

    protected $fillable = ['coddep', 'nomdep'];

    protected $hidden = ['created_at', 'updated_at'];

    public function toDomain(EloquentDepartment $eloquentDepartment): Department
    {
        return new Department(
          coddep: $eloquentDepartment->coddep,
          nomdep: $eloquentDepartment->nomdep
        );
    }
}
