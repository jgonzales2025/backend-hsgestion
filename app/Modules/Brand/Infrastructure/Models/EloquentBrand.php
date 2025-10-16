<?php

namespace App\Modules\Brand\Infrastructure\Models;

use App\Modules\Brand\Domain\Entities\Brand;
use Illuminate\Database\Eloquent\Model;

class EloquentBrand extends Model
{
    protected $table = 'brands';
    protected $fillable = ['name', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

     public function toDomain(EloquentBrand $eloquentbran): Brand
    {
        return new Brand(
            id: $eloquentbran->id,
            name: $eloquentbran->name,
            status: $eloquentbran->status,
        );
    }

}
