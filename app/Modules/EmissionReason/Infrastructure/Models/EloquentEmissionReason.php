<?php

namespace App\Modules\EmissionReason\Infrastructure\Models;

use App\Modules\EmissionReason\Domain\Entities\EmissionReason;
use Illuminate\Database\Eloquent\Model;

class EloquentEmissionReason extends Model
{
    protected $table = 'emission_reasons';

    protected $fillable = ['description', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function toDomain(EloquentEmissionReason $emission_reason): EmissionReason
    {
        return new EmissionReason(
            id: $emission_reason->id,
            description: $emission_reason->description,
            status: $emission_reason->status
        );
    }
}
