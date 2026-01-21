<?php

namespace App\Modules\WarrantyStatus\Infrastructure\Model;

use App\Modules\WarrantyStatus\Domain\Entities\WarrantyStatus;
use Illuminate\Database\Eloquent\Model;

class EloquentWarrantyStatus extends Model
{
    protected $table = 'warranty_statuses';

    protected $fillable = ['name', 'color', 'st_warranty', 'st_support', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function toDomain(EloquentWarrantyStatus $warrantyStatus): ?WarrantyStatus
    {
        return new WarrantyStatus(
            id: $warrantyStatus->id,
            name: $warrantyStatus->name,
            color: $warrantyStatus->color,
            status: $warrantyStatus->status,
            st_warranty: $warrantyStatus->st_warranty,
            st_support: $warrantyStatus->st_support
        );
    }
}