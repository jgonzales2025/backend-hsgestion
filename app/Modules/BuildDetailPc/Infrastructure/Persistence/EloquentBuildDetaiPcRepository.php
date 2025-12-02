<?php

namespace App\Modules\BuildDetailPc\Infrastructure\Persistence;

use App\Modules\BuildDetailPc\Domain\Interface\BuildDetailPcRepositoryInterface;
use App\Modules\BuildDetailPc\Infrastructure\Models\EloquentBuildDetailPc;
use App\Modules\BuildDetailPc\Domain\Entities\BuildDetailPc;

class EloquentBuildDetaiPcRepository implements BuildDetailPcRepositoryInterface
{
    public function findAll(): array
    {
        $buildDetailPc =  EloquentBuildDetailPc::with('article')->get();

        return $buildDetailPc->map(function ($buildDetailPc) {
            return new BuildDetailPc(
                id: $buildDetailPc->id,
                build_pc_id: $buildDetailPc->build_pc_id,
                article_id: $buildDetailPc->article_id,
                quantity: $buildDetailPc->quantity,
                price: $buildDetailPc->price,
                subtotal: $buildDetailPc->subtotal,
                cod_fab: $buildDetailPc->article->cod_fab ?? null,
                description: $buildDetailPc->article->description ?? null
            );
        })->toArray();
    }
    public function findById(int $id): ?BuildDetailPc
    {
        $buildDetailPc = EloquentBuildDetailPc::with('article')->find($id);
        if (!$buildDetailPc) return null;

        return new BuildDetailPc(
            id: $buildDetailPc->id,
            build_pc_id: $buildDetailPc->build_pc_id,
            article_id: $buildDetailPc->article_id,
            quantity: $buildDetailPc->quantity,
            price: $buildDetailPc->price,
            subtotal: $buildDetailPc->subtotal,
            cod_fab: $buildDetailPc->article->cod_fab ?? null,
            description: $buildDetailPc->article->description ?? null
        );
    }

    public function findByBuildPcId(int $buildPcId): array
    {
        $details = EloquentBuildDetailPc::with('article')->where('build_pc_id', $buildPcId)->get();

        return $details->map(function ($detail) {
            return new BuildDetailPc(
                id: $detail->id,
                build_pc_id: $detail->build_pc_id,
                article_id: $detail->article_id,
                quantity: $detail->quantity,
                price: $detail->price,
                subtotal: $detail->subtotal,
                cod_fab: $detail->article->cod_fab ?? null,
                description: $detail->article->description ?? null
            );
        })->toArray();
    }

    public function create(BuildDetailPc $data): ?BuildDetailPc
    {
        $buildDetailPc = EloquentBuildDetailPc::create([
            'build_pc_id' => $data->getBuildPcId(),
            'article_id' => $data->getArticleId(),
            'quantity' => $data->getQuantity(),
            'price' => $data->getPrice(),
            'subtotal' => $data->getSubtotal(),
        ]);
        // Reload to get relationship
        $buildDetailPc->load('article');

        return new BuildDetailPc(
            id: $buildDetailPc->id,
            build_pc_id: $buildDetailPc->build_pc_id,
            article_id: $buildDetailPc->article_id,
            quantity: $buildDetailPc->quantity,
            price: $buildDetailPc->price,
            subtotal: $buildDetailPc->subtotal,
            cod_fab: $buildDetailPc->article->cod_fab ?? null,
            description: $buildDetailPc->article->description ?? null
        );
    }

    public function update(BuildDetailPc $data): ?BuildDetailPc
    {
        $buildDetailPc = EloquentBuildDetailPc::find($data->getId())->update([
            'build_pc_id' => $data->getBuildPcId(),
            'article_id' => $data->getArticleId(),
            'quantity' => $data->getQuantity(),
            'price' => $data->getPrice(),
            'subtotal' => $data->getSubtotal(),
        ]);
        $buildDetailPc = EloquentBuildDetailPc::with('article')->find($data->getId());

        return new BuildDetailPc(
            id: $buildDetailPc->id,
            build_pc_id: $buildDetailPc->build_pc_id,
            article_id: $buildDetailPc->article_id,
            quantity: $buildDetailPc->quantity,
            price: $buildDetailPc->price,
            subtotal: $buildDetailPc->subtotal,
            cod_fab: $buildDetailPc->article->cod_fab ?? null,
            description: $buildDetailPc->article->description ?? null
        );
    }

    public function deleteByBuildPcId(int $buildPcId): void
    {
        EloquentBuildDetailPc::where('build_pc_id', $buildPcId)->delete();
    }
}
