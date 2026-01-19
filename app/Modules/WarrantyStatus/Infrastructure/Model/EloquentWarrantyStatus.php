<?php

namespace App\Modules\WarrantyStatus\Infrastructure\Model;

use App\Modules\WarrantyStatus\Domain\Entities\WarrantyStatus;
use Illuminate\Database\Eloquent\Model;

class EloquentWarrantyStatus extends Model
{
    protected $table = 'warranty_statuses';

    protected $fillable = ['name', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function toDomain(EloquentWarrantyStatus $warrantyStatus): WarrantyStatus
    {
        return new WarrantyStatus(
            id: $warrantyStatus->id,
            name: $warrantyStatus->name,
            status: $warrantyStatus->status
        );
    }
}