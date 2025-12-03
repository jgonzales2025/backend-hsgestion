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
            'quantity' => $data->getQuantity(),
            'article_ensamb_id' => $data->getArticleEnsambId(),
        ]);
        return new BuildPc(
            id: $buildPc->id,
            name: $buildPc->name,
            description: $buildPc->description,
            total_price: $buildPc->total_price,
            user_id: $buildPc->user_id,
            status: $buildPc->status,
            quantity: $buildPc->quantity,
            article_ensamb_id: $buildPc->article_ensamb_id,
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
                quantity: $buildPc->quantity,
                article_ensamb_id: $buildPc->article_ensamb_id,
            );
        }
        return null;
    }

    public function findAll(?string $search, ?int $is_active)
    {
        $buildPcs = EloquentBuildPc::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->when(!is_null($is_active), function ($query) use ($is_active) {
                $query->where('status', $is_active);
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        // Transformar la colección de resultados
        $buildPcs->getCollection()->transform(function ($buildPc) {
            return new BuildPc(
                id: $buildPc->id,
                name: $buildPc->name,
                description: $buildPc->description,
                total_price: $buildPc->total_price,
                user_id: $buildPc->user_id,
                status: $buildPc->status,
                quantity: $buildPc->quantity,
                article_ensamb_id: $buildPc->article_ensamb_id,
            );
        });

        return $buildPcs;
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
            'quantity' => $data->getQuantity(),
            'article_ensamb_id' => $data->getArticleEnsambId(),
        ]);
        return new BuildPc(
            id: $buildPc->id,
            name: $buildPc->name,
            description: $buildPc->description,
            total_price: $buildPc->total_price,
            user_id: $buildPc->user_id,
            status: $buildPc->status,
            quantity: $buildPc->quantity,
            article_ensamb_id: $buildPc->article_ensamb_id,
        );
    }
}
