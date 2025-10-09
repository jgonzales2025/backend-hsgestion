<?php

namespace App\Modules\TransportCompany\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentTransportCompany extends Model
{
    protected $table = 'transport_companies';

    protected $fillable = ['ruc', 'company_name', 'address', 'nro_reg_mtc', 'status'];

    protected $hidden = ['created_at', 'updated_at'];
}
