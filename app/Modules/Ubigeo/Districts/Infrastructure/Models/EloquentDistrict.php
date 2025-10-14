<?php

namespace App\Modules\Ubigeo\Districts\Infrastructure\Models;

use App\Modules\Ubigeo\Districts\Domain\Entities\District;
use Illuminate\Database\Eloquent\Model;

class EloquentDistrict extends Model
{
    protected $table = 'districts';
    protected $fillable = ['coddep', 'codpro', 'coddis', 'nompro'];
    protected $hidden = ['created_at', 'updated_at'];

    public function toDomain(EloquentDistrict $eloquentDistrict): District
    {
        return new District(
            coddep: $eloquentDistrict->coddep,
            codpro: $eloquentDistrict->codpro,
            coddis: $eloquentDistrict->coddis,
            nomdis: $eloquentDistrict->nomdis
        );
    }
}
