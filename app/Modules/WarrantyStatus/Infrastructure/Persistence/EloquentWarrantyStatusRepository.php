<?php

namespace App\Modules\WarrantyStatus\Infrastructure\Persistence;

use App\Modules\WarrantyStatus\Domain\Entities\WarrantyStatus;
use App\Modules\WarrantyStatus\Domain\Interfaces\WarrantyStatusRepositoryInterface;
use App\Modules\WarrantyStatus\Infrastructure\Model\EloquentWarrantyStatus;

class EloquentWarrantyStatusRepository implements WarrantyStatusRepositoryInterface
{
    public function findAll(?int $type): array
    {
        $eloquentWarrantyStatuses = EloquentWarrantyStatus::query()->where('status', 1);

        if ($type == 1) {
            $eloquentWarrantyStatuses = $eloquentWarrantyStatuses->where('st_warranty', 1);
        } elseif ($type == 2) {
            $eloquentWarrantyStatuses = $eloquentWarrantyStatuses->where('st_support', 1);
        }
        
        $eloquentWarrantyStatuses = $eloquentWarrantyStatuses->get();

        return $eloquentWarrantyStatuses->map(fn($warrantyStatus) => $this->mapToEntity($warrantyStatus))->toArray();
    }

    public function findById(int $id): WarrantyStatus
    {
        $eloquentWarrantyStatus = EloquentWarrantyStatus::find($id);

        return $this->mapToEntity($eloquentWarrantyStatus);
    }

    private function mapToEntity(EloquentWarrantyStatus $eloquentWarrantyStatus): WarrantyStatus
    {
        return new WarrantyStatus(
            id: $eloquentWarrantyStatus->id,
            name: $eloquentWarrantyStatus->name,
            color: $eloquentWarrantyStatus->color,
            status: $eloquentWarrantyStatus->status,
            st_warranty: $eloquentWarrantyStatus->st_warranty,
            st_support: $eloquentWarrantyStatus->st_support
        );
    }
}