<?php

namespace App\Modules\IngressReason\Infrastructure\Models;

use App\Modules\IngressReason\Domain\Entities\IngressReason;
use Illuminate\Database\Eloquent\Model;

class EloquentIngressReason extends Model
{
    protected $table = 'ingress_reasons';

    protected $fillable = ['description', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function toDomain(EloquentIngressReason $eloquentIngressReason): IngressReason
    {
        return new IngressReason(
            id: $eloquentIngressReason->id,
            description: $eloquentIngressReason->description,
            status: $eloquentIngressReason->status,
        );
    }
}
