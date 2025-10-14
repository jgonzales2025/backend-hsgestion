<?php

namespace App\Modules\IngressReason\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentIngressReason extends Model
{
    protected $table = 'ingress_reasons';

    protected $fillable = ['description', 'status'];

    protected $hidden = ['created_at', 'updated_at'];
}
