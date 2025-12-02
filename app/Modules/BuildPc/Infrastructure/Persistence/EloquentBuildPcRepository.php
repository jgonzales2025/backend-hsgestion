<?php

namespace App\Modules\BuildPc\Infrastructure\Persistence;

use App\Modules\BuildPc\Domain\Entities\BuildPc;
use App\Modules\BuildPc\Domain\Interface\BuildPcRepositoryInterface;
use App\Modules\BuildPc\Infrastructure\Models\EloquentBuildPc;

class EloquentBuildPcRepository implements BuildPcRepositoryInterface
{
    public function create(BuildPc $data): ?BuildPc
    {
        $buildPc = EloquentBuildPc::create([
            'id' => $data->getId(),
            'name' => $data->getName(),
            'description' => $data->getDescription(),
            'total_price' => $data->getTotalPrice(),
            'user_id' => $data->getUserId(),
            'status' => $data->getStatus(),
        ]);
        return new BuildPc(
            id: $buildPc->id,
            name: $buildPc->name,
            description: $buildPc->description,
            total_price: $buildPc->total_price,
            user_id: $buildPc->user_id,
            status: $buildPc->status,
        );
    }

    public function findById(int $id): ?BuildPc
    {
        $buildPc = EloquentBuildPc::find($id);
        if ($buildPc) {
            return new BuildPc(
                id: $buildPc->id,
                name: $buildPc->name,
                description: $buildPc->description,
                total_price: $buildPc->total_price,
                user_id: $buildPc->user_id,
                status: $buildPc->status,
            );
        }
        return null;
    }

    public function findAll(): array
    {
        $buildPcs = EloquentBuildPc::all();
        return $buildPcs->map(function ($buildPc) {
            return new BuildPc(
                id: $buildPc->id,
                name: $buildPc->name,
                description: $buildPc->description,
                total_price: $buildPc->total_price,
                user_id: $buildPc->user_id,
                status: $buildPc->status,
            );
        })->toArray();
    }
    public function update(BuildPc $data): ?BuildPc
    {
        $buildPc = EloquentBuildPc::find($data->getId());
        if (!$buildPc) {
            return null;
        }
        $buildPc->update([
            'name' => $data->getName(),
            'description' => $data->getDescription(),
            'total_price' => $data->getTotalPrice(),
            'user_id' => $data->getUserId(),
            'status' => $data->getStatus(),
        ]);
        return new BuildPc(
            id: $buildPc->id,
            name: $buildPc->name,
            description: $buildPc->description,
            total_price: $buildPc->total_price,
            user_id: $buildPc->user_id,
            status: $buildPc->status,
        );
    }
}
