<?php

namespace App\Modules\TransportCompany\Infrastructure\Models;

use App\Modules\TransportCompany\Domain\Entities\TransportCompany;
use Illuminate\Database\Eloquent\Model;

class EloquentTransportCompany extends Model
{
    protected $table = 'transport_companies';

    protected $fillable = ['ruc', 'company_name', 'address', 'nro_reg_mtc', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function toDomain(EloquentTransportCompany $transportCompany): TransportCompany
    {
        return new TransportCompany(
            id: $transportCompany->id,
            ruc: $transportCompany->ruc,
            company_name: $transportCompany->company_name,
            address: $transportCompany->address,
            nro_reg_mtc: $transportCompany->nro_reg_mtc,
            status: $transportCompany->status
        );
    }
}
