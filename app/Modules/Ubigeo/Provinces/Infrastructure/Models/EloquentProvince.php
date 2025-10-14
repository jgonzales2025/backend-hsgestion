<?php

namespace App\Modules\Ubigeo\Provinces\Infrastructure\Models;

use App\Modules\Ubigeo\Provinces\Domain\Entities\Province;
use Illuminate\Database\Eloquent\Model;

class EloquentProvince extends Model
{
    protected $table = 'provinces';
    protected $fillable = ['coddep', 'codpro', 'nompro'];
    protected $hidden = ['created_at', 'updated_at'];

    public function toDomain(EloquentProvince $eloquentProvince): Province
    {
        return new Province(
          coddep: $eloquentProvince->coddep,
          codpro: $eloquentProvince->codpro,
          nompro: $eloquentProvince->nompro
        );
    }
}
